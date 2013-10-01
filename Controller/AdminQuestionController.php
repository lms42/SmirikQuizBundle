<?php

namespace Smirik\QuizBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\QuizBundle\Controller\Base\AdminQuestionController as BaseController;

class AdminQuestionController extends BaseController
{
    
    public function indexAction($page = false)
    {
        $this->setup();
        $this->getPaginate();

        $this->page = (int) $page;
        $this->generateRoutes();

        $sort      = $this->getRequest()->query->get('sort', 'id');
        $sort_type = $this->getRequest()->query->get('sort_type', 'desc');
        $filter    = $this->getRequest()->query->get('filter', false);
        $options   = $this->getRequest()->query->get('options', false);

        if ($options) {
            $options = json_decode($options);
        }

        $collection_query = $this->get('admin.request.process.manager')->sort($this->getQuery(), $sort, $sort_type);
        $collection_query = $this->get('admin.request.process.manager')->filter($collection_query, $filter);

        $collection = $collection_query
            ->groupBy('Id')
            ->paginate($this->page, $this->limit);

        $response = array(
            'collection' => $collection,
            'page'       => $this->page,
            'limit'      => $this->limit,
            'columns'    => $this->get('admin.data.grid')->getColumns(),
            'layout'     => $this->layout,
            'actions'    => $this->get('admin.data.grid')->getActions(),
            'routes'     => $this->routes,
            'grid'       => $this->get('admin.data.grid'),
            'name'       => $this->name,
            'sort'       => $sort,
            'sort_type'  => $sort_type,
            'filter'     => json_encode($filter),
            'filter_raw' => $filter,
            'options'    => $options,
            'nativeActions' => $this->get('admin.data.grid')->getNativeActions()
        );

        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->render($this->get('admin.data.grid')->template('index_content'), $response);
        }

        return $this->render($this->get('admin.data.grid')->template('index'), $response);
    }
    
}

