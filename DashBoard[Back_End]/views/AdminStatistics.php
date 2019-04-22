<?php 
include('includes/Header.php');
if(!$employee->getGroupID() == "0")
{
    header("Location: DashBoard.php");
    exit();
}
?>
<div class="container-fluid">
    <h1 class="text-center"> Statistics Page</h1>
    <br>
    <div class="col-xs-12" style="background-color: #fff2f2;">
        <form class="form-inline" style="padding: 20px;" onsubmit="cat_serviceStatistics(this); return false;">
            <label class="text-danger col-xs-12 text-center">services in each category</label><br><br>
            <!--from-->
            <div class="input-group">
              <div class="input-group-addon">From</div>
              <input type="date" class="form-control col-xs-2" name="from" value="2017-01-01">
            </div>
            <!--to-->
            <div class="input-group">
                <div class="input-group-addon">To</div>
                <input type="date" class="form-control col-xs-2" name="to" value="2017-12-31">
            </div>

            <div class="input-group">
                <select name="bartype" class="form-control col-xs-2">
                    <option disabled selected>bar type</option>
                    <option value="bar" >Vertical bar</option>
                    <option value="horizontalBar" >Horizontal bar</option>
                </select>
            </div>

            <div class="input-group">
                <select name="pietype" class="form-control col-xs-2">
                    <option disabled selected>pie type</option>
                    <option value="pie">pie</option>
                    <option value="doughnut">doughnut</option>
                </select>
            </div>

            <div class="input-group">
                <button class="btn btn-default">   Show Result        </button>
            </div>

        </form>
        <div class="row col-xs-12">
            <!-- start graph div -->
                <div class="col-xs-8" style="margin-bottom: 25px; padding: 5px;">
                    <div class="col-xs-12 servicesBarsStatsContainer" style="height: 350px; background-color: #fff; ">
                        <canvas id="servicesBarsStats" style="padding-bottom: 10px;" ></canvas>
                    </div>
                </div>
            <!-- -->
            <!-- start graph div -->
                <div class="col-xs-4" style="margin-bottom: 25px; padding: 5px;">
                    <div class="col-xs-12 servicespieStatsContainer" style="height: 350px; background-color: #fff;">
                        <canvas id="servicespieStats" style="height: 300px; width: 300px;"></canvas>
                    </div>
                </div>
            <!-- -->

        </div>
    </div>
    
    <!-- Second graph -->
    <div class="col-xs-12" style="background-color: #fff2f2; margin:25px 0px 10px 0px;">
        <label class="text-danger col-xs-12 text-center" style="padding: 20px 0px 10px ;" >Users Flow</label><br><br>
        <form class="form-inline" style="padding: 20px;" onsubmit="usersStatistics(this); return false;">
            <!--from-->
            <div class="input-group">
                <div class="input-group-addon">From</div>
                <input type="date" class="form-control col-xs-2" name="from" value="2017-01-01">
            </div>
            <!--to-->
            <div class="input-group">
                <div class="input-group-addon">To</div>
                <input type="date" class="form-control col-xs-2" name="to" value="2017-12-31">
            </div>
            <div class="input-group">
                <button class="btn btn-default">   Show Result        </button>
            </div>
        </form>
        <div class="row col-xs-12">
            <!-- start graph div -->
                <div class="col-xs-12" style="padding: 5px;">
                    <div class="col-xs-12 clientsStatisticsContainer" style="height: 350px; background-color: #fff; ">
                        <canvas id="clientsStatistics" style="padding-bottom: 10px;" ></canvas>
                    </div>
                </div>
            <!--End graph div -->
        </div>
    </div>
    
</div>
<?php 
 echo'
        <script src="../style/js/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
        <script src="../style/js/AdminStatistics.js"></script>'; 
 include('includes/Footer.php'); 