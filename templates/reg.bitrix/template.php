	Регистрация<br>
	<form action="<?=$arResult['REG_BITRIX_PATH']?>" class="col-lg-2" onsubmit="regBitrix.reg(event,this)" >
	  <div class="form-group">
	    <label for="exampleInputEmail1">Email </label><br>
	    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputPassword1">Пароль</label>
	    <input type="password" class="form-control" id="exampleInputPassword1">
	  </div>
	    <div class="form-group">
	    <label for="exampleInputPassword1">Повторный пароль</label>
	    <input type="password" class="form-control" id="exampleInputPassword1">
	  </div>
	  <button type="submit" class="btn btn-primary">Зарегестрироватся</button>
	</form>
</div>