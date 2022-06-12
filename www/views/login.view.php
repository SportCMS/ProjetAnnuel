
	<img class="logo__login" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">

<?php if(!empty($errors)): ?>
					<?php foreach($errors as $error): ?>
						<p class="error__login"> <?=$error?> </p>
					<?php endforeach ?>
				
        <?php endif ?>

<?php $this->includePartial("form", $user->getLoginForm());?>
