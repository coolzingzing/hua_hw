<?php
use Aws\Sqs\SqsClient;

require_once  JPATH_ADMINISTRATOR . '\components\com_bliss\vendor\autoload.php';
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

class BlissControllerService extends JControllerLegacy {

	public function push(){
		 $client = $this-> getSqlClient();
		 $data = [
		 	'task'=>'send.donation.info',
		    'callback' =>['Class','method'],
		    'order_id' =>123,
			 'order'=>[]
		 ];

		 $payload = json_encode($data);

		$id = $client -> sendMessage([
			'QueueUrl'=> $this->getQueueUrl($client),
				'MessageBody' => json_encode($data)
			])->get('MessageId');

		echo $id;
	}

	public function getQueueUrl($client){
		return	$client ->getQueueUrl(['QueueName' =>'test'])->get('QueueUrl');
	}

	public function getSqlClient(){
		return new SqsClient(array(
			'region'      => 'us-west-2',
			'version'     => 'latest',
			'credentials' => [
				'key' => 'AKIAJUFZWA3AIOD7XJ5Q',
				'secret'  => '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'
			]
		));
	}

	public function pop()
	{
		$client = $this->getSqlClient();

		$result = $client->receiveMessage([
			'QueueUrl' => $this->getQueueUrl($client),
			'AttributeNames' => ['ApproximateReceiveCount']
		]);

		if(!$result['Messages']){
			//return;
			echo "no new message";
			die;
		}
		$message = $result['Messages'][0];

		$body = json_decode($message['Body'],true);

		$retryTimes = $message['Attributes']['ApproximateReceiveCount'];

		try{

			if($retryTimes >= 5){
				$client->deleteMessage([
					'QueueUrl' => $this->getQueueUrl($client),
					'ReceiptHandle' => $message['ReceiptHandle']
				]);

				echo 'Max attempts exceeded';
				die;
			}

			switch ($body['task'])
			{
				case 'send.donation.info':
					throw new \Exception('Failed');
				//		$this->donation();
				//@ Do success
						break;
				//More tasks
			}

			//@ Delete Message
			$client->deleteMessage([
				'QueueUrl' => $this->getQueueUrl($client),
				'ReceiptHandle' => $message['ReceiptHandle']
			]);

			echo "Success";

		}catch (\Exception $e){
			//@ Release messsage back
			if($retryTimes +1 >= 5){
				$client->deleteMessage([
					'QueueUrl' => $this->getQueueUrl($client),
					'ReceiptHandle' => $message['ReceiptHandle']
				]);
			}

			$client->changeMessageVisibility([
				'QueueUrl' => $this->getQueueUrl($client),
				'ReceiptHandle' => $message['ReceiptHandle'],
				'VisibilityTimeout' =>1
			]);

			echo "Failed,Release back; retryTimes=".$retryTimes;
		}
	//	print_r($result);

		die;
	}

	public function donation(){
		$http = JHttpFactory::getHttp();

		$params = JComponentHelper::getParams('com_bliss');
	//	$url = $params->get('Aic.Endpoint');
	//	$hashKey    = $params->get('Aic.HashKey');
	//	$hashIV     = $params->get('Aic.HashIV');


		$url =  $params->get('Aic.Endpoint');
		$key = $params->get('Aic.HashKey');
		$iv = $params->get('Aic.HashIV');
		$data = [
			'check_value' => '',
			'version' => '1.0',
			'payment_id' => time(),
			'pay_date' => JFactory::getDate()->toSql(),
			'payer_id' => 'A123456789',
			'payer_name' => 'ABC',
			'payer_email' => 'foo@foo.com',
			'payer_contact_phone' => '21234567',
			'total_amount' => 1200,
			'receipt_type_code' => 'Y',
			'address_type_code' => 'LEGACY',
			'zip_code' => 105,
			'zone1' => '台北市',
			'zone2' => '松山區',
			'mail_legacy' => '南京東路１段3號8樓',
			'mail_others' => '',
			'donator_type_code' => '1',
			'recipient_name' => 'ABC',
			'send_time' => JFactory::getDate()->toSql(),
			'detail_data' => [
				[
					'dona_use_name' => '慈心',
					'donator_name' => 'ABC',
					'receipt_amount' => 1200,
					'is_publicly' => 'Y',
					'will_to_upload_nta' => 'Y',
					'donator_id' => ''
				]
			]
		];
		//		$json = json_decode($json, true);
		$c = [
			'version'    => $data['version'],
			'payment_id' => $data['payment_id'],
			'pay_date'   => $data['pay_date'],
			'payer_id'   => $data['payer_id'],
			'total_amount' => $data['total_amount'],
			'send_time'  => $data['send_time']
		];
		ksort($c);
		$c = http_build_query($c);
		$c = urldecode($c);
		$c = "hashkey=$key&$c&hashiv=$iv";
		$c = strtoupper(hash('sha256', $c));
		$data['check_value'] = $c;




		/*	$http = JHttpFactory::getHttp();

			$url = 'http://localhost:81/hua_hw/bin/test.php?foo=bar';

			$response = $http->post($url,['flower'=>'sakura']);*/

		//	if($response->code >= 200 && $response < 300)
		try{
			$response = $http->post($url,json_encode($data),['Content-Type'=>'application/json']);

			if($response->code !== 200)
			{
				//save failed data

				throw new \RuntimeException('HTTP fail'.$response->code,$response->code);

				//redirect
			}

		}catch (\Exception $e){
			//save failed data
		}

			//echo $response->body;
			print_r(json_decode($response->body));
			die;
	}
}