<div style='padding: 10px 10px;'>결과:</div>

<div>
    @if($status == 200)
        <div>회원가입 되었습니다. 귀하의 아이디는 <span>[{{ $argv['email'] }}]</span>입니다. </div>
    @elseif($status == 401)
        <div>{{ $argv['message'] }}</div>
    @elseif($status == 5002)
        <div>로그인 Error</div>
    @elseif($status == 5003)
        <div>데이터 DB INSERT Error</div>
    @endif
</div>

<?php
/*

if($status == 5001){
    echo "<br> 회원가입 Error <br>";
    echo $messages;
    exit;
} else if($status == 5002){
    echo "<br> 로그인 Error <br>";
    echo $messages;
    exit;
} else if($status == 5003){
    echo "회원가입 DB 저장에 실패하였습니다. ";
    exit;
} else if($status == 200){
    echo "회원가입 되었습니다. <br><Br> 귀하의 아이디는 [". $userEmail ."] 입니다. ";
    exit;
}
*/
?>