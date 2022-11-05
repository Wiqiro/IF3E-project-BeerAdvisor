<?php

require_once("connection.php");
global $bdd;


$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Aroma, Style, Color, Last_modified, AVG(C.grade) AS Grade FROM beer B LEFT JOIN comment C ON B.id = C.beer_id ";
if (isset($_GET['search'])) {
	$query = $query . "WHERE name LIKE '%" . $_GET['search'] . "%'";
	
	if ($_GET['BeerColor'] != '') {
		$query = $query . " AND color = '" . $_GET['BeerColor'] . "'";
	}
}
$query = $query . " GROUP BY B.id ORDER BY ";

if (isset($_GET['SortBy'])) {
	$sorting = $_GET['SortBy'];
	switch ($sorting) {
	case "RatingDesc":
		$query = $query . "Grade DESC";
		break;
	case "RatingAsc":
		$query = $query . "Grade ASC";
		break;
	case "DateDesc":
		$query = $query . "Last_modified DESC";
		break;
	case "DateAsc":
		$query = $query . "Last_modified ASC";
		break;
	case "NameAsc":
		$query = $query . "Name ASC";
		break;
	case "NameDesc":
		$query = $query . "Name DESC";
		break;
	case "AlcAsc":
		$query = $query . "Alcohol ASC";
		break;
	case "AlcDesc":
		$query = $query . "Alcohol DESC";
		break;
	default:
		break;
	}
} else {
	$query = $query . "Last_modified DESC";
}

echo $query;
$request = $bdd->prepare($query);
$request->execute();
$data = $request->fetch();
	
?>

<html>
   <head>
		<meta charset ="UTF-8">
		<meta name = "author" content="Quentin,Eloi,William">
		<meta name ="description" content="This is a page about beer">
		<link rel="shortcut icon" href="" type="image/x-icon">
		<link rel="stylesheet" href="style.css" type="text/css">
		<title>Beer advisor</title>
	</head>
	<body>

		<form action="" method="get">

			<input type="text" name="search" id="search" placeholder="Search a beer"><input type="submit" value="Search">

			<label for="SortBy">Sort by</label>
			<select name="SortBy" id="SortBy">
				<option value="RatingDesc">Rating: High to low</option>
				<option value="RatingAsc">Rating: Low to High</option>
				<option value="DateDesc">Date: New to old</option>
				<option value="DateAsc">Date: Old to new</option>
				<option value="NameAsc">Name: A-Z</option>
				<option value="NameDesc">Name: Z-A</option>
				<option value="AlcAsc">Alcohol: Low to High</option>
				<option value="AlcDesc">Alcohol: High to Low</option>

			</select>
			<label for="BeerColor">Color</label>
			<select name="BeerColor" id="BeerColor">
				<option></option>
				<option value="PaleStraw">Pale straw</option>
				<option value="Straw">Straw</option>
				<option value="PaleGold">Pale gold</option>
				<option value="DeepGold">Deep gold</option>
				<option value="PaleAmber">Pale amber</option>
				<option value="MediumAmber">Medium amber</option>
				<option value="DeepAmber">Deep Amber</option>
				<option value="AmberBrown">Amber brown</option>
				<option value="Brown">Brown</option>
				<option value="RubyBrown">Ruby brown</option>
				<option value="DeepBrown">Deep brown</option>
				<option value="Black">Black</option>
			</select>
			
		</form>

		<div class="BeerSearchResults">
			<?php
			while ($data != null) {
				echo '<a href="Show-Beer.php?id=' . $data['ID'] . '" class="BeerContainer onclick="location.href=`google.com`">
				<h3><strong>Name: ' . $data['Name'] . '</strong></h3>
				Last modified: ' . $data['Last_modified'] . '<br>
				Avg grade: ' . number_format($data['Grade'], 1) . ' / 5<br>
				Alc: ' . $data['Alcohol'] . '<br>
				Style: ' . $data['Style'] . '<br>
				Color: ' . $data['Color'] . '<br>
				Aroma: ' . $data['Aroma'] . '<br>
				
				</a><br><br>'	;

				$data = $request->fetch();
			}?>
			
		</div>
		
	</body>
</html>