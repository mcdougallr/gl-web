<?php
include ("food-header.php");

if (isset($_GET['sid'])) {$section_id = cleantext($_GET['sid']);}
else {$section_id = "";}

?>

<link rel="stylesheet" href="_gl-food.css">

<!-- Start Main Window -->
<?php
if ($section_id != 0)
{
	$stmt = $conn->prepare("SELECT session_program_code, session_number, section_name FROM ss_session_sections
								LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
								WHERE section_id = :section_id");
		$stmt->bindValue(':section_id', $section_id);
		$stmt->execute();
		$section = $stmt->fetch(PDO::FETCH_ASSOC); 
}

$stmt = $conn->prepare("SELECT * FROM fd_packs WHERE pack_section_id = :pack_section_id ORDER BY pack_name, pack_type, pack_date");
$stmt->bindValue(':pack_section_id', $section_id);
$stmt->execute();
$packs = $stmt->fetchAll();	
?>

<h1>
	<?php 
	if ($section_id != 0){print $section['session_program_code'].$section['session_number']." - ".$section['section_name'];}
	else {print "Pre Summer"; }?>
	<a style="font-size: .7em; padding-left: 5px;vertical-align: 1px;" class="plaintext-button" href="reports/print-packing-list.php?sid=<?php print $section_id; ?>" target="_blank"><i class="fa fa-print"style=""></i></a>
</h1>

<div id="food-table">
	<form class="pure-form pure-form-stacked" method=post action="">
		<table>
			<tr>
				<th style="text-align: left">Pack Name</th>
				<th>Pack Type</th>
				<th>Pack Date</th>
				<th>#</th>
				<th><i class="fa fa-trash"></i></th>
				<th><i class="fa fa-print"></i></th>
			</tr>
			<?php
			foreach ($packs as $pack)
			{?>      	
				<tr id="t-<?php print $pack['pack_id']; ?>">
					<td style="text-align: left"><a class="plaintext-button" href="edit-pack.php?pid=<?php print $pack['pack_id']; ?>"><?php print $pack['pack_name']; ?></a></td>
					<td><?php print $pack['pack_type']; ?></td>
					<td><?php print date('Y M d', strtotime($pack['pack_date'])); ?></td>
					<td><?php print $pack['pack_groups_in_session']; ?></td>
					<td><i id="del-pack-<?php print $pack['pack_id']; ?>" class="fa fa-trash plaintext-button delete-button" style="cursor: pointer;"></i></td>
					<td>
						<a target="_blank" class="plaintext-button" href="reports/print-packing-list.php?pid=<?php print $pack['pack_id']; ?>">
						<i class="fa fa-print"></i></a>
					</td>
				</tr>	
			<?php 
			} ?>
			<tr><td colspan=6><a class="plaintext-button" href="edit-pack.php?sid=<?php print $section_id; ?>"><i class="fa fa-plus plaintext-button"></i> Add New Pack</a></td></tr>
		</table>
	</form>
</div>

<?php include ("food-footer.php"); ?>

<script>
	$(document).ready(function(){
		$('.print-button').click(function(e) {
			e.preventDefault;
			id = $(this).attr("id");
			pack_id = id.replace("print-pack-", "");
			page = "reports/print-packing-list.php?pid=" + pack_id;
			//alert(page);return false;
			window.location.href = page;
			return false;
		});
		
		$('.delete-button').click(function(e) {
			e.preventDefault;
			if (confirm("Are you sure you want to delete this pack?") == true)
			{
				id = $(this).attr("id");
				pack_id = id.replace("del-pack-", "");
				tid = "#t-" + pack_id;
				//alert(tid); return false;
				$.ajax({
					type: "POST",
					url: "process-edit-pack-delete.php",
					data: {pack_id: pack_id},
					success: function() 
					{
						$(tid).hide();
					}
				});	
				return false;
			}
		});					
	});	
</script>