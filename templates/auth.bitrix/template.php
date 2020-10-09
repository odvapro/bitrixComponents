<div class="row  justify-content-center form-group-3">
	Авторизация<br>
	<form action="<?=$arResult['AUTH_BITRIX_PATH']?>" class="col-lg-2" onsubmit="authBitrix.reg(event,this)">
	  <div class="form-group">
	    <label for="exampleInputEmail1">Email address</label>
	    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputPassword1"> Пароль</label>
	    <input type="password" class="form-control" id="exampleInputPassword1">
	  </div>
	  <button type="submit" class="btn btn-primary">Авторизоватся</button>
	</form>
