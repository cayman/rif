<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        $ip = $_SERVER['REMOTE_ADDR'];
        $request = $errors->request;
        $ajax=$request->isXmlHttpRequest();

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $auth = Zend_Controller_Action_HelperBroker::getStaticHelper('Login');
                Log::path($auth->getRole(),urldecode($request->getRequestUri()),$errors->type,Zend_Log::WARN);
                $this->view->message = 'Page not found';
                $this->_helper->messenger($this->view->message);
                if(!$ajax) $this->_redirect('/');
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = $errors->exception->getMessage();
                $this->_helper->messenger ($this->view->message);
                Log::crit($this,"[$ip] $errors->type -".$errors->exception);
                if($errors->exception instanceof Site_Service_Exception)
                    if($errors->exception->isRedirect() && !$ajax)
                        $this->_redirect($errors->exception->getRedirect());
                break;
        }

         // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request   = $request;
        $this->render(null,'error');
    }



}

