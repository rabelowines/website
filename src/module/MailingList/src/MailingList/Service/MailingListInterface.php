<?php

namespace MailingList\Service;

use Zend\Stdlib\RequestInterface as Request;

interface MailingListInterface
{
    public function subscribe($email, Request $request);
}