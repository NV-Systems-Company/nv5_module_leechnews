<!-- BEGIN: main -->
<div class="text-center">
	<strong>{MSG1}</strong>
	<br />
	<br />
	<em class="fa fa-spinner fa-spin fa-2x"></em>
	<br />
	<br />
	<strong><a href="{NV_REDIRECT}">{MSG2}</a></strong>
</div>

<!-- BEGIN: meta_refresh -->
<meta http-equiv="refresh" content="{TIME_BACK};url={NV_REDIRECT}" />
<!-- END: meta_refresh -->
<!-- BEGIN: go_back -->
<script type="text/javascript">
	setTimeout('history.back()',3000)
</script>
<!-- END: go_back -->

<!-- END: main -->