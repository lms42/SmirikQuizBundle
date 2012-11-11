<?php

namespace Smirik\QuizBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\AnswerQuery;
use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\QuizBundle\Model\QuestionQuery;
use Smirik\QuizBundle\Model\UserQuestionQuery;
use Smirik\QuizBundle\Model\UserQuizQuery;
use Smirik\QuizBundle\Model\UserQuiz;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\Question;
use Smirik\QuizBundle\Form\Type\QuizType;

/**
 * Quiz controller.
 *
 * @Route("/quiz")
 */
class QuizController extends Controller
{

  /**
  * Show user avaliable quizes.
  *
  * @Route("/", name="smirik_quiz_index")
  * @Secure(roles="ROLE_USER")
  * @Template("SmirikQuizBundle:Quiz:index.html.twig")
  */
  public function indexAction()
  {
    $user = $this->container->get('security.context')->getToken()->getUser();

    $active_quiz = UserQuizQuery::create()
    	->filterByUserId($user->getId())
    	->filterByIsActive(true)
    	->filterByIsClosed(false)
    	->find();

    /**
    * Get quiz ids for active quizes
    */
    $active_quiz_ids = array();
    if ($active_quiz)
    {
        foreach ($active_quiz as $quiz)
        {
            $active_quiz_ids[] = $quiz->getQuizId();
        }
    }

    $avaliable_quiz = QuizQuery::create()->filterByIsOpened(true)->find();
    $completed_users_quiz = UserQuizQuery::create()
        ->filterByUserId($user->getId())
    	->filterByIsClosed(true)
    	->find();

    return array(
        'users_quiz'      => $active_quiz,
        'active_quiz_ids' => $active_quiz_ids,
        'avaliable_quiz'  => $avaliable_quiz,
        'completed_users_quiz'  => $completed_users_quiz,
    );
  }

  /**
   * List of active quized
   * @Route("/my", name="smirik_quiz_my")
   * @Template("SmirikQuizBundle:Quiz:my.html.twig", vars={"get"})
   */
  public function myAction()
  {
    $user = $this->container->get('security.context')->getToken()->getUser();
    $uqm  = $this->get('user_quiz.manager');
    
    $active_quiz = $uqm->getAllActiveQuizForUser($user, true, $this->getDoctrine()->getEntityManager());
    /**
     * Get quiz ids for active quizes
     */
    $active_quiz_ids = array();
    if ($active_quiz)
    {
      foreach ($active_quiz as $quiz)
      {
        $active_quiz_ids[] = $quiz->getQuizId();
      }
    }
    
    $avaliable_quiz = $this->get('quiz.manager')->getAvaliableQuizes($user);
    $completed_users_quiz = $uqm->getAllCompletedQuizForUser($user);
    
    return array(
      'users_quiz'      => $active_quiz,
      'active_quiz_ids' => $active_quiz_ids,
      'avaliable_quiz'  => $avaliable_quiz,
      'completed_users_quiz'  => $completed_users_quiz,
    );
  }

  /**
   * Start new quiz for current user
   * @Route("/start/{quiz_id}", name="smirik_quiz_start")
   * @Template("SmirikQuizBundle:Quiz:start.html.twig", vars={"get"})
   */
  public function startAction($quiz_id)
  {
    $user = $this->container->get('security.context')->getToken()->getUser();

    /**
     * @todo validate $quiz_id
     */
    $quiz = QuizQuery::create()->findPk($quiz_id);

    /**
     * Redirect to home if quiz is not opened
     */
    if (!$quiz->getIsOpened()) 
    {
      return $this->redirect($this->generateUrl('smirik_quiz_index'));
    }
		
		$user_quiz = $qm->findOrCreateUserQuiz($user->getId(), $quiz);
		
    return array(
      'quiz'      => $quiz,
      'user_quiz' => $user_quiz,
    );

  }

    /**
    * Start new quiz for current user
    * @Route("/question/quiz{uq_id}/step{number}", name="smirik_quiz_go")
    * @Template("SmirikQuizBundle:Quiz:question.html.twig", vars={"get"})
    */
    public function questionAction($uq_id, $number)
    {

        $user = $this->container->get('security.context')->getToken()->getUser();

        $user_quiz = UserQuizQuery::create()->findPk($uq_id);
        $quiz = $user_quiz->getQuiz();

        if (!$user_quiz)
        {
            throw $this->createNotFoundException('UserQuiz not found');
        }
        if ($user_quiz->getUserId() != $user->getId())
        {
            throw $this->createNotFoundException('UserQuiz is not valid');
        }

        /**
        * @todo validate $uq_id
        * @todo validate time
        */
        if (!$this->checkActiveUserQuiz($user_quiz, $user))
        {
            return $this->redirect($this->generateUrl('smirik_quiz_final', array('user_quiz_id' => $user_quiz->getId())));
        }

        $questions = json_decode($user_quiz->getQuestions());
        $number = (int)$number;
        if (($number > count($questions)) || ($number < 0) || (empty($questions)))
        {
            /**
            * @todo Exception
            */
            throw new \Exception('Quiz data exception');
        }

        $question = QuestionQuery::create()->findPk($questions[$number]);

        /**
        * Find UserQuestion for current question (to provide default answer value)
        */
        $user_question = UserQuestionQuery::create()
            ->current($user->getId(), $user_quiz->getId(), $question->getId())
            ->findOne();

        if ($user_question)
        {
            $current_answer = $user_question->getAnswer();
            $current_answer_id = $current_answer->getId();
        } else
        {
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

	public function checkActiveUserQuiz($user_quiz, $user)
	{
		if (!$user_quiz->isActiveForUser($user))
		{
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
        $number       = $this->getRequest()->request->get('number', false); 

        if ((!$user_quiz_id) || (!$question_id) || (!$answer_id))
        {
            throw $this->createNotFoundException('Error in application: no required parameters');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $user_quiz = UserQuizQuery::create()->findPk($user_quiz_id);

        if ($user_quiz->getUserId() != $user->getId())
        {
            throw $this->createNotFoundException('UserQuiz is not valid');
        }

        $quiz = $user_quiz->getQuiz();

        if (!$user_quiz)
        {
            throw $this->createNotFoundException('Quiz for current user not found');
        }

        $this->checkActiveUserQuiz($user_quiz, $user);

        $answer = AnswerQuery::create()->findPk($answer_id);
        if (!$answer)
        {
            throw $this->createNotFoundException('Answer not found');
        }

        $question = QuestionQuery::create()->findPk($question_id);
        if (!$question)
        {
            throw $this->createNotFoundException('Question not found');
        }

          /**
           * Find answer for current question or create new UserQuestion
           * and add/replace answer
           */
        $user_question = UserQuestionQuery::create()
        	->current($user->getId(), $user_quiz->getId(), $question->getId())
        	->findOne();
        if (!is_object($user_question))
        {
            $user_question = new UserQuestion();
            $user_question->setUserId($user->getId());
            $user_question->setQuizId($user_quiz->getQuiz()->getId());
            $user_question->setQuestionId($question->getId());
            $user_question->setUserQuizId($user_quiz->getId());
            $user_question->save();
        }
        $user_question->addAnswer($answer, $answer_text);

        /**
        * Increment current position in quiz. If this question is the last one redirect to end page.
        */
        if (($number + 1) == $quiz->getNumQuestions())
        {
            return $this->redirect($this->generateUrl('smirik_quiz_prefinal', array('user_quiz_id' => $user_quiz->getId())));
        }

        $user_quiz->setCurrent($number+1);
        $user_quiz->save();

        return $this->redirect($this->generateUrl('smirik_quiz_go', array('uq_id' => $user_quiz->getId(), 'number' => $user_quiz->getCurrent())));
    }

  /**
   * Page before end
   * @Route("/question/quiz{user_quiz_id}/prefinal", name="smirik_quiz_prefinal")
   * @Template("SmirikQuizBundle:Quiz:prefinal.html.twig", vars={"get"})
   */
  public function preFinalAction($user_quiz_id)
  {
    $user_quiz = UserQuizQuery::create()->findPk($user_quiz_id);
    if (!$user_quiz)
    {
      throw $this->createNotFoundException('UserQuiz not found');
    }
    
    if ($user_quiz->getIsClosed())
    {
      return $this->redirect($this->generateUrl('smirik_quiz_index'));
    }
    
    return array(
      'user_quiz' => $user_quiz,
    );
  }
  
  /**
   * Final page
   * @Route("/question/quiz{user_quiz_id}/final", name="smirik_quiz_final")
   * @Template("SmirikQuizBundle:Quiz:final.html.twig", vars={"get"})
   */
  public function finalAction($user_quiz_id)
  {
    $user = $this->container->get('security.context')->getToken()->getUser();
    
    /**
     * Closing UserQuiz
     */
    $user_quiz = UserQuizQuery::create()->findPk($user_quiz_id);
    if (!$user_quiz)
    {
      throw $this->createNotFoundException('UserQuiz not found');
    }
    
    if (!$user_quiz->getIsClosed())
    {
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
        $user = $this->get('security.context')->getToken()->getUser();
		$qm   = $this->get('quiz.manager');
	    
	    $user_quiz = $qm->getQuizesForUser($user->getId());
		
		return array(
			'user'            => $user,
			'user_quizes'     => $user_quiz,
		);
    }
  
}
