<?php
/**
 * Author: mark m /
 * Date:4/27/2020 7:03 PM
 * Email: markmei36@hotmail.com
 *
 */

namespace app\api\controller\v1;


use app\api\service\BaseToken;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Controller;

class BaseController extends Controller
{
    protected function appAndSuperUserScope(){
        $scope = (new BaseToken)->getScopeByToken();
        if($scope){
            if (($scope >= ScopeEnum::APP_USER)){
                return true;
            }
            else{

                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }

    }

    protected function appUserOnlyScope(){
        $scope = (new BaseToken)->getScopeByToken();
        if($scope){
            if (($scope == ScopeEnum::APP_USER)){
                return true;
            }
            else{

                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }

    }

    protected function superUserOnlyScope(){
        $scope = (new BaseToken)->getScopeByToken();
        if($scope){
            if (($scope > ScopeEnum::APP_USER)){
                return true;
            }
            else{

                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }

    }


}