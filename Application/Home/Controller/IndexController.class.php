<?php

namespace Home\Controller;

class IndexController extends CommonController {

    public function index(){
        $this->redirect('Vip/index');
    }
}