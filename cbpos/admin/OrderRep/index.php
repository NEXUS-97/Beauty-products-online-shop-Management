<style>
    table td,table th{
        padding: 3px !important;
    }
</style>
<?php 
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] :  date("Y-m-d",strtotime(date("Y-m-d")." -7 days")) ;
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] :  date("Y-m-d") ;
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">Order Report</h5>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row align-items-end">
                <div class="form-group col-md-3">
                    <label for="date_start">Date Start</label>
                    <input type="date" class="form-control form-control-sm" name="date_start" value="<?php echo date("Y-m-d",strtotime($date_start)) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="date_start">Date End</label>
                    <input type="date" class="form-control form-control-sm" name="date_end" value="<?php echo date("Y-m-d",strtotime($date_end)) ?>">
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-success btn-sm" type="button" id="printBTN"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </form>
        <hr>
        <div id="printable">
            <div class="row row-cols-2 justify-content-center align-items-center" id="print_header" style="display:none">
                <div class="col-1">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="<?php echo $_settings->info('short_name') ?>" width="75px" heigth="75px">
                </div>
                <div class="col-7">
                    <h4 class="text-center m-0"><?php echo $_settings->info('name') ?></h4>
                    <h3 class="text-center m-0"><b>Order Report</b></h3>
                    <?php if($date_start != $date_end): ?>
                    <p class="text-center m-0">Date Between <?php echo date("M d,Y", strtotime($date_start)) ?> and <?php echo date("M d,Y", strtotime($date_end)) ?></p>
                    <?php else: ?>
                    <p class="text-center m-0">As of <?php echo date("M d,Y", strtotime($date_start)) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <hr>

            <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="35%">
                    
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference Code</th>
                        <th>Delivery Address</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
           

                     
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $gtotal = 0;
                        $qry = $conn->query("SELECT * FROM `orders` where date(date_created) between '{$date_start}' and '{$date_end}' order by unix_timestamp(date_created) desc ");
                        while($row = $qry->fetch_assoc()):
                            
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo $row['ref_code'] ?></td>
                            <td><?php echo $row['delivery_address'] ?></td>
                            <td><?php echo $row['payment_method'] ?></td>
                            <td><?php echo $row['amount'] ?></td>
                        
                            
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<noscript>
    <style>
        .m-0{
            margin:0;
        }
        .text-center{
            text-align:center;
        }
        .text-right{
            text-align:right;
        }
        .table{
            border-collapse:collapse;
            width: 100%
        }
        .table tr,.table td,.table th{
            border:1px solid gray;
        }
    </style>
</noscript>
<script>
    $(function(){
        $('table th, table td').addClass("px-1 py-2 align-middle")
        $('#filter-form').submit(function(e){
            e.preventDefault()
            location.href = "./?page=OrderRep&date_start="+$('[name="date_start"]').val()+"&date_end="+$('[name="date_end"]').val()
        })

        $('#printBTN').click(function(){
            var head = $('head').clone();
            var rep = $('#printable').clone();
            var ns = $('noscript').clone().html();
            start_loader()
            rep.prepend(ns)
            rep.prepend(head)
            rep.find('#print_header').show()
            var nw = window.document.open('','_blank','width=900,height=600')
                nw.document.write(rep.html())
                nw.document.close()
                setTimeout(function(){
                    nw.print()
                    setTimeout(function(){
                        nw.close()
                        end_loader()
                    },200)
                },300)
        })
    })
</script>