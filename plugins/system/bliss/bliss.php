<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

class PlgSystemBliss extends JPlugin{
	public function onContentPrepare($context)
	{
	//	echo $context;
		if($context!= 'com_content.category'){
			return;
		}
		echo "1234";
	}

	protected $allow_context = [
		'com_users.profile',
		'com_users.user',
		'com_users.registration',
		'com_admin.profile'
	];

	public function onContentPrepareForm(JForm $form, $data)
	{
		$context = $form->getNAme();
		//print_r($context);

		if(!in_array($context,$this->allow_context)){//
			return;
		}
			$form->removeField('username');//拿掉帳號欄位
	}

	//拿出JForm時,順便把欄位塞進去 (讀取或儲存)
	public function onContentPrepareData($context, $data)
	{
		if(!in_array($context,$this->allow_context)){//
			return;
		}
		//print_r($data);

		if(isset($data->email1)){//如果email1 真的存在時,才執行
			$data->username = $data->email1;//data 是物件,不是array
			//把email蓋到username,帳號就是email
		}
	}

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		///print_r($data);//陣列
		if($result){
			$id = $data['id'];
			$email = $data['email'];
			$db = JFactory::getDbo();
			$query = $db -> getQuery(true);
			$query -> update('#__users')
				->set('username =' .$query->q($email))
				->where('id='.$id);

			$db->setQuery($query)->execute;
		}
		return true;
	}
}