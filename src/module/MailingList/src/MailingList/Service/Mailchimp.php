<?php

namespace MailingList\Service;

use Zend\Stdlib\RequestInterface as Request;
use MailingList\Exception\AlreadySubscribed;
use MailingList\Exception;

class Mailchimp implements MailingListInterface
{
	protected $mc;
	protected $listID;

	public function __construct(\Mailchimp $mc, $listID)
	{
		$this->mc = $mc;
		$this->listID = $listID;
	}

	protected function getMailchimp()
	{
		return $this->mc;
	}

	public function subscribe($email, Request $request)
	{
		if (!$this->listID) {
			throw new Exception('Missing list ID');
		}

		$lists = new \Mailchimp_Lists($this->getMailchimp());
		
		try {
	        $sub = $lists->subscribe(
	            $this->listID,
	            array(
	                'email' => $email,
	            ),
	            array(
	                'optin_ip'    => $request->getServer()->get('REMOTE_ADDR'),
	                'optin_time'  => $request->getServer()->get('REQUEST_TIME'),
	                'mc_location' => array(
	                    'anything' => 'something',
	                ),
	            )
	        );

	        if (is_array($sub) && isset($sub['email']) && $sub['email'] == $email) {
	            return true;
	        }
	    } catch (\Mailchimp_List_AlreadySubscribed $e) {
	    	throw new AlreadySubscribed($e->getMessage());
	    } catch (\Mailchimp_Error $e) {
	    	throw new Exception($e->getMessage());
	    }

	    throw new Exception('Failed to subscribe user');
	}
}