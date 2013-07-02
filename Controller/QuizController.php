<?php

namespace Smirik\QuizBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Smirik\QuizBundle\Model\AnswerQuery;
use Smirik\QuizBundle\Model\QuestionQuery;
use Smirik\QuizBundle\Model\UserQuestionQuery;
use Smirik\QuizBundle\Model\UserQuizQuery;

/**
 * Quiz controller.
 *
 * @Route("/quiz")
 */
class QuizController extends Controller
{
    /**
     * Show user available quizes.
     *
     * @Route("/", name="smirik_quiz_index")
     * @Secure(roles="ROLE_USER")
     * @Template("SmirikQuizBundle:Quiz:index.html.twig")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $uqm  = $this->get('user_quiz.manager');
        $qm   = $this->get('quiz.manager');

        $active_quiz = $uqm->active($user);

        /**
         * Get quiz ids for active quizes
         */
        $active_quiz_ids      = array_keys($active_quiz->toArray('QuizId'));
        $available_quiz       = $qm->available();
        $completed_users_quiz = $uqm->completed($user);

        return array(
            'users_quiz'           => $active_quiz,
            'active_quiz_ids'      => $active_quiz_ids,
            'available_quiz'       => $available_quiz,
            'completed_users_quiz' => $completed_users_quiz,
        );
    }

    /**
     * Start new quiz for current user
     * @Route("/start/{quiz_id}", name="smirik_quiz_start")
     * @Template("SmirikQuizBundle:Quiz:start.html.twig", vars={"get"})
     * @ParamConverter("quiz", options={ "mapping"={ "quiz_id" : "id" }})
     */
    public function startAction(\Smirik\QuizBundle\Model\Quiz $quiz)
    {
        $user = $this->getUser();
        $user_quiz_manager = $this->get('user_quiz.manager');

        /**
         * Redirect to home if quiz is not opened
         */
        if (!$quiz->getIsOpened()) {
            return $this->redirect($this->generateUrl('smirik_quiz_index'));
        }

        $user_quiz = $user_quiz_manager->findOrCreate($user, $quiz);

        return array(
            'quiz' => $quiz,
            'user_quiz' => $user_quiz,
        );

    }

    /**
     * Start new quiz for current user
     * @Route("/question/quiz{uq_id}/step{number}", name="smirik_quiz_go")
     * @ParamConverter("user_quiz", options={ "mapping"={ "uq_id" : "id" }})
     * @Template("SmirikQuizBundle:Quiz:question.html.twig", vars={"get"})
     */
    public function questionAction(\Smirik\QuizBundle\Model\UserQuiz $user_quiz, $number)
    {
        $user = $this->getUser();

        if (!$this->checkActiveUserQuiz($user_quiz, $user)) {
            return $this->redirect($this->generateUrl('smirik_quiz_final', array('user_quiz_id' => $user_quiz->getId())));
        }

        $questions = json_decode($user_quiz->getQuestions());
        $number = (int)$number;
        if (($number > count($questions)) || ($number < 0) || (empty($questions))) {
            throw new \Exception('Quiz data exception');
        }

        $question = QuestionQuery::create()->joinAnswer('a', 'left join')->findPk($questions[$number]);

        /**
         * Find UserQuestion for current question (to provide default answer value)
         */
        $user_question = UserQuestionQuery::create()
            ->current($user->getId(), $user_quiz->getId(), $question->getId())
            ->findOne();

        if ($user_question) {
            $current_answer = $user_question->getAnswer();
            $current_answer_id = $current_answer->getId();
        } else {
            $current_answer = false;
            $current_answer_id = false;
        }
        $answers = $question->getAnswers();

        return array(
            'user_quiz'         => $user_quiz,
            'quiz'              => $user_quiz->getQuiz(),
            'question'          => $question,
            'user_question'     => $user_question,
            'number'            => $number,
            'total'             => count($questions),
            'current_answer'    => $current_answer,
            'current_answer_id' => $current_answer_id,
            'answers'           => $answers,
            'num_answers'       => count($answers),
        );
    }

    private function checkActiveUserQuiz($user_quiz, $user)
    {
        if (!is_object($user_quiz))
        {
            return false;
        }
        if (!$user_quiz->isActiveForUser($user)) {
            $user_quiz->close();
            return false;
        }
        return true;
    }

    /**
     * Check user answer
     * @Route("/question/check", name="smirik_quiz_check")
     * @Method("post")
     */
    public function checkQuestionAction(Request $request)
    {
        $user_quiz_id = $this->getRequest()->request->get('user_quiz_id', false);
        $question_id  = $this->getRequest()->request->get('question_id', false);
        $answer_id    = $this->getRequest()->request->get('answer_id', false);
        $answer_text  = $this->getRequest()->request->get('answer_text', false);

        $number = $this->getRequest()->request->get('number', false);

        if ((!$user_quiz_id) || (!$question_id) || (!($answer_id || $answer_text)) ) {
            throw $this->createNotFoundException('Error in application: no required parameters');
        }

        $user = $this->getUser();
        $user_quiz = UserQuizQuery::create()->findPk($user_quiz_id);

        if (!$this->checkActiveUserQuiz($user_quiz, $user)) {
            return $this->redirect($this->generateUrl('smirik_quiz_final', array('user_quiz_id' => $user_quiz->getId())));
        }

        $quiz = $user_quiz->getQuiz();

        $answer = AnswerQuery::create()->findPk($answer_id);
        if (!$answer) {
            throw $this->createNotFoundException('Answer not found');
        }

        $question = QuestionQuery::create()->findPk($question_id);
        if (!$question) {
            throw $this->createNotFoundException('Question not found');
        }

        /**
         * Find answer for current question or create new UserQuestion
         * and add/replace answer
         */
        $user_question_manager = $this->get('user_question.manager');
        $user_question = $user_question_manager->saveAnswer($user, $user_quiz, $question, $answer, $answer_text);

        /**
         * Increment current position in quiz. If this question is the last one redirect to end page.
         */
        if (($number + 1) == $quiz->getNumQuestions()) {
            return $this->redirect($this->generateUrl('smirik_quiz_prefinal', array('user_quiz_id' => $user_quiz->getId())));
        }

        $user_quiz->setCurrent($number + 1);
        $user_quiz->save();

        return $this->redirect($this->generateUrl('smirik_quiz_go', array('uq_id' => $user_quiz->getId(), 'number' => $user_quiz->getCurrent())));
    }

    /**
     * Page before end
     * @Route("/question/quiz{user_quiz_id}/prefinal", name="smirik_quiz_prefinal")
     * @Template("SmirikQuizBundle:Quiz:prefinal.html.twig", vars={"get"})
     * @ParamConverter("user_quiz", options={ "mapping"={ "user_quiz_id" : "id" }})
     */
    public function preFinalAction(\Smirik\QuizBundle\Model\UserQuiz $user_quiz)
    {
        $user = $this->getUser();
        if (!$this->checkActiveUserQuiz($user_quiz, $user)) {
            return $this->redirect($this->generateUrl('smirik_quiz_final', array('user_quiz_id' => $user_quiz->getId())));
        }

        return array(
            'user_quiz' => $user_quiz,
        );
    }

    /**
     * Final page
     * @Route("/question/quiz{user_quiz_id}/final", name="smirik_quiz_final")
     * @Template("SmirikQuizBundle:Quiz:final.html.twig", vars={"get"})
     * @ParamConverter("user_quiz", options={ "mapping"={ "user_quiz_id" : "id" }})
     */
    public function finalAction(\Smirik\QuizBundle\Model\UserQuiz $user_quiz)
    {
        $user = $this->getUser();

        if (!$user_quiz->getIsClosed()) {
            $user_quiz->close();
        }

        return array(
            'user_quiz' => $user_quiz,
        );
    }

    /**
     * @Route("/results", name="quiz_results")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function resultsAction()
    {
        $user = $this->getUser();
        $uqm  = $this->get('user_quiz.manager');

        $user_quiz = $uqm->get($user);

        return array(
            'user'        => $user,
            'user_quizes' => $user_quiz,
        );
    }

}
