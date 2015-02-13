<form class="cntct-frm" role="form" id="feedbackForm">
<div class="form-group">
<input type="text" class="form-control" id="name" name="name" placeholder="Name">
<span class="help-block" style="display: none;">Please enter your name.</span>
</div>
<div class="form-group">
<input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
<span class="help-block" style="display: none;">Please enter a valid e-mail address.</span>
</div>
<div class="form-group">
<textarea rows="10" cols="100" class="TA-FC form-control" id="message" name="message" placeholder="Message"></textarea>
<span class="help-block" style="display: none;">Please enter a message.</span>
</div>
<img class="captcha" id="captcha" src="/assets/forms/contact/securimage_show.php" alt="CAPTCHA Image" />
<a href="#" onclick="document.getElementById('captcha').src = '/assets/forms/contact/securimage_show.php?' + Math.random(); return false" class="btn-ol btn btn-info btn-outline" title="Reset code"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a><br/>
<div class="form-group" style="margin-top: 10px;">
<input type="text" class="form-control" name="captcha_code" id="captcha_code" placeholder="For security, please enter the code displayed in the box." />
<span class="help-block" style="display: none;">Please enter the code displayed within the image.</span>
</div>
<span class="help-block" style="display: none;">Please enter a the security code.</span>
<button type="submit" id="feedbackSubmit" class="btn-lg btn btn-primary btn-outline" style="display: block; margin-top: 10px;">Send Feedback</button>
</form>