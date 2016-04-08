<?php

namespace RabeloWines\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Log\LoggerInterface;

use RabeloWines\Model\Signup;
use RabeloWines\Form\SignupForm;
use MailingList\Service\MailingListInterface;
use MailingList\Exception;
use MailingList\Exception\AlreadySubscribed;

class IndexController extends AbstractActionController
{
    protected $ml;

    protected $loger;

    public function __construct(MailingListInterface $ml, LoggerInterface $logger)
    {
        $this->ml = $ml;
        $this->logger = $logger;
    }

    public function indexAction()
    {
        $form = new SignupForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $signup = new Signup();

            $form->setInputFilter($signup->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $signup->exchangeArray($form->getData());

                try {
                    $this->ml->subscribe($signup->email, $request, $signup->listID);

                    // add flash message letting them know they are already subscribed
                    $this->flashMessenger()->setNamespace('success')->addMessage(
                        'Thank you for subscribing to our mailing list'
                    );
                } catch (AlreadySubscribed $e) {
                    // add flash message letting them know they are already subscribed
                    $this->flashMessenger()->setNamespace('info')->addMessage(
                        'You are already subscribed to our mailing list'
                    );
                } catch (Exception $e) {
                    // other errors, log signup but pretend it all went ok
                    $this->logSignup($request, $signup);
                }

                return $this->redirect()->toRoute('home');
            }
        }

        return new ViewModel(
            array('form' => $this->params()->fromRoute('form', $form))
        );
    }

    private function logSignup(Request $request, Signup $signup)
    {
        $this->logger->err('FAILED SIGNUP: '.json_encode(array(
            'list'       => 'ABC123',
            'email'      => $signup->email,
            'optin_ip'   => $request->getServer()->get('REMOTE_ADDR'),
            'optin_time' => $request->getServer()->get('REQUEST_TIME'),
        )));
    }
}
