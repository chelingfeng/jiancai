<?php

namespace Home\Controller;

use Think\Controller;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use GuzzleHttp\Client;

class ApiController extends Controller {

	private $post;

	public function __construct()
	{
		parent::__construct();
		$this->post = json_decode(file_get_contents('php://input'), true) ?? [];
	}

	public function login()
	{
		if (ArrayToolkit::requireds($this->post, ['avatarUrl', 'nickName', 'code'])) {
			$openid = $this->getOpenid($this->post['code']);
			$user = M('user')->where(['openid' => $openid])->find();
			if ($user) {
				M('user')->where(['id' => $user['id']])->save([
					'nickname' => urlencode($this->post['nickName']),
					'avatar' => $this->post['avatarUrl'],
					'update_time' => date('Y-m-d H:i:s'),
				]);
			} else {
				M('user')->add([
					'openid' => $openid,
					'nickname' => urlencode($this->post['nickName']),
					'avatar' => $this->post['avatarUrl'],
					'update_time' => date('Y-m-d H:i:s'),
					'create_time' => date('Y-m-d H:i:s'),
					'create_time' => date('Y-m-d H:i:s'),
					'card_sn' => date('YmdHis', time()) . mt_rand(100, 999),
				]);
			}
			$this->ajaxReturn(codeReturn(0, ['openid' => $openid]));
		} else {
			$this->ajaxReturn(codeReturn(10001));
		}
	}

	private function getOpenid($code)
	{
		$client = new Client();
		$appid = setting('system')['appid'];
		$secret = setting('system')['secret'];
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

		$response = $client->get($url);
		$body = json_decode($response->getBody(), true);
		return isset($body['errcode']) ? '' : $body['openid'];
	}
}