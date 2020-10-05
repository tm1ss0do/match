<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

use Illuminate\Support\Facades\Validator;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Requests\StoreMessageRequest;



class StoreMessageRequestTest extends TestCase
{
  use RefreshDatabase;
  /**
    * カスタムリクエストのバリデーションテスト
    *
    * @param string 項目名
    * @param string 値
    * @param boolean 期待値(true:バリデーションOK、false:バリデーションNG)
    * @dataProvider dataproviderStoreMessageRequest
    */
   public function testValidation($item, $data, $expect)
   {
       //入力項目（$item）とその値($data)
       $dataList = [$item => $data];

       $request = new StoreMessageRequest();
       //フォームリクエストで定義したルールを取得
       $rules = $request->rules();

       //Validatorファサードでバリデーターのインスタンスを取得、その際に入力情報とバリデーションルールを引数で渡す
       $validator = Validator::make($dataList, $rules);
       //入力情報がバリデーショルールを満たしている場合はtrue、満たしていな場合はfalseが返る
       $result = $validator->passes();
       //期待値($expect)と結果($result)を比較
       $this->assertEquals($expect, $result);
   }

   public function dataproviderStoreMessageRequest()
   {
       return [
           '正常' => ['content', '内容', true],
           '必須エラー' => ['content', '', false],
           //str_repeat('a', 256)で、256文字の文字列を作成(aが256個できる)
           '最大文字数エラー' => ['content', str_repeat('a', 1001), false],
       ];
   }


}
