<li class="parent @if($sidebar == 'message') active @endif">
	<a href="{{url('/communications/send-message')}}">
		<i class="icon-loop icons "></i>
		<span>Send Message</span>
	</a>
</li>

<li class="parent @if($sidebar == 'template') active @endif">
	<a href="{{url('/communications/sms-template')}}">
		<i class="icon-layers icons "></i>
		<span>SMS Templates</span>
	</a>
</li>

<li class="parent @if($sidebar == 'email-template') active @endif">
	<a href="{{url('/communications/email-template')}}">
		<i class="icon-layers icons "></i>
		<span>EMAIL Templates</span>
	</a>
</li>

<li class="parent @if($sidebar == 'communication') active @endif" >
	<a  href="{{url('/communications')}}">
		<i class="icon-folder icons "></i>
		<span>Logs</span>
	</a>
</li>
