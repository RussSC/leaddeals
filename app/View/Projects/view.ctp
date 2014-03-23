<h2><?php echo $project['Project']['title'];?></h2>
<hr />
<p><?php echo $project['Project']['description'];?></p>
<h6><?php echo $this->Time->niceShort(
	$project['Project']['released']);?></h6>