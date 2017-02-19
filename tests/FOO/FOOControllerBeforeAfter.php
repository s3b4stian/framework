<?php

/**
 * Linna App.
 *
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\FOO;

use Linna\Mvc\Controller;

class FOOControllerBeforeAfter extends Controller
{
    public function __construct(FOOModel $model)
    {
        parent::__construct($model);
    }

    public function beforeModifyDataTimed()
    {
        $this->model->addToData('before');
    }
    
    public function modifyDataTimed()
    {
        $this->model->modifyDataTimed();
    }

    public function afterModifyDataTimed()
    {
        $this->model->addToData('after');
    }
    
    public function before()
    {
        //do nothing
    }

    public function after()
    {
        //do nothing
    }
}
