<?php 
require './includes/header.php';
$category = new Category();
$MainCategory = $client->viewMainCategory($category);
?>
<script>document.title="Categoreis";</script>
<section class="viewCategory">
    <div class="container">
        <div class="row">
            <div class="ThirdSectionHeader viewCategorySection">
                <h2>Categories list</h2>
            </div>
        </div>
        <div class="row">
            <?PHP
            if($MainCategory)
            {
                foreach ($MainCategory as $MainCat) {
                    echo 
                    ' 
                        <div class="col-xs-4 categoryContainer">
                            <ul class="MainList">
                                <li><a style="color:inherit;" href="viewServices.php?cat_id='.$MainCat['cat_id'].'">'.$MainCat['name'].' </a><i class="fa fa-list-ol pull-right"></i></li>
                                    ';
                                     $subCategory = $client->viewSubCategory($category , $MainCat['cat_id']); 
                                     if($subCategory)
                                    {
                                        echo '<ul class="subList">';
                                        foreach ($subCategory as $subCat) {
                                            echo '
                                                   <li><a style="color:inherit;" href="viewServices.php?cat_id='.$subCat['cat_id'].'">'.$subCat['name'].'</a></li>
                                                 ';
                                        }
                                        echo '</ul>';
                                     }
                                    else
                                    {
                                         echo '<h4 class="text-center" style="padding:20px;">There is No Sub Category</h4>';
                                    }
                                echo'
                            </ul>
                        </div>
                    ';
                }
            }
            else
            {
                echo '<h3>there is No Sub Category</h3>';
            }
            ?>
        </div>
    </div>
</section>
<?php 
require './includes/footer.php'; 