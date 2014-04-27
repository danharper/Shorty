<title>Shorty</title>

<?php if ($error) : ?>
	<style>.error { color: red; }</style>
	<p class="error"><b>Error:</b> <?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" action="/">
	<input name="url">
	<button>Shorten it, Shorty!</button>
</form>
