<?php
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>

    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic|Dosis:200,300,400,500,600,700,800|Abel|Droid+Sans:400,700|Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic|Lora:400,700,400italic,700italic|PT+Sans:400,700,400italic,700italic|PT+Sans+Narrow:400,700|Quicksand:300,400,700|Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic|Lobster|Ubuntu+Condensed|Oxygen:400,300,700|Oswald:700,400,300|Open+Sans+Condensed:300,700,300italic|Roboto+Condensed:300italic,400italic,700italic,400,700,300|Open+Sans:300italic,400italic,600italic,700italic,800italic,800,700,400,600,300|Prosto+One|Francois+One|Comfortaa:700,300,400|Raleway:300,600,900,500,400,100,800,200,700|Roboto:300,700,500italic,900,300italic,400italic,900italic,100italic,100,500,400,700italic|Roboto+Slab:300,700,100,400|Share:700,700italic,400italic,400' rel='stylesheet' type='text/css'
>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/style_less.css" />
    
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    body.error {
    background:#f2f2f2;
    }
    .errorbox{
        margin: 15% 0 0;
        width: 50%;
    }
    h1{
      color: @errorboxTitleh3;
      font-size: 100px;
      line-height: 70px;
    }
    h3{
      color: @errorboxTitleh3;
    }
    a{
      cursor: pointer;
      color: @mainColor;
    }
</style>
</head>
<body class="error">
<center>
    <div class="errorbox">

    <div class="block">
        <h1>404</h1>
	<h3>Page not found</h3>
    </div>
	<p>
	    Sorry! The page you are looking for cannot be found. Please use 
	    the provided search box to find what you are looking for, 
	    click on our top navigational menu, or 
	    <a onclick="window.history.back()">go back.</a>
	</p>

    </div>
</center>
</body>
</html>