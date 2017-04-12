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

		//lock email
		if(!empty($data->id)){//有id時,不允許改email
			$form->setFieldAttribute('email1','disabled',true);
			$form->setFieldAttribute('email2','disabled',true);
		}


		//Load our own profiles
		$form->loadFile(__DIR__.'/forms/profile.xml');
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

		//讀取資料時顯示出custom field
		//load profile data if exists
		if(isset($data->id)){
			$db= JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__bliss_user_profiles')
				->where('user_id='.$data->id);
			$profile=$db->setQuery($query)->loadObject();

			$data->profile = $profile;//自己撈出來塞給data
		}
	}

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		///print_r($data);//陣列
		if($result){
			$id = $data['id'];
			$email = $data['email'];

			//username override
			$db = JFactory::getDbo();
			$query = $db -> getQuery(true);
			$query -> update('#__users')
				->set('username =' .$query->q($email))
				->where('id='.$id);

			$db->setQuery($query)->execute;

			//save profile
			if(isset($data['profile']) && is_array($data['profile']))
				{
					$profile = $data['profile'];
					$this->saveProfile($data['id'],$profile);
				}
		}
		//print_r($data);//看地址是否出現
		//die;

		//notics joomla that user save success
		return true;
	}

	protected function saveProfile($id,array $profile){
		$db= JFactory::getDbo();
		$query = $db->getQuery(true);
		$data = (object) $profile;
		$data->user_id = $id;
		//check user profile exists
		$query->select('id')
			->from('#__bliss_user_profiles')
			->where('user_id='.$id);

		$exists = $db->setQuery($query)->loadResult();
		if(!$exists){
			//insert new one
			$db->insertObject('#__bliss_user_profiles',$data);
		}else{
			//update old one
			$db->updateObject('#__bliss_user_profiles',$data,'user_id');
		}
	}
}