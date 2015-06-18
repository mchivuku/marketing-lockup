@extends('app')
@section('content')
    <meta name="_token" content="{{ csrf_token() }}" />
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Your Signatures</div>

                    <div class="panel-body">
                        <p>Below is a list of all the signatures created by you.</p>
                        <?php
                        $itemsCollection = ($pagedata['tabledata']);
                        $usersArray = $itemsCollection -> toArray();
                            $GLOBALS['userrole'] = $pagedata['userRole'];
                        ?>
                        <table class="table" id="signaturesTable">
                            <thead>
                            <tr>
                               <th>
                                   Username
                               </th>
                                <th width="100px">
                                   Preview
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Comments
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $itemsCollection -> each(function($signature){

                            ?>
                            <tr id="{{{$signature -> signatureid}}}">
                               <td>
                                   {{{$signature -> userid}}}
                               </td>
                                <td class="sPrev">
                                    <!-- Signature Preview -->
                                    <a href="#" class="signaturePreview">Preview</a>

                                    <label style="display:none;">
                                        <?php echo $signature -> preview ;?>
                                    </label>
                                </td>
                                <td class="statusCol">
                                    {{{$signature -> status}}}
                                </td>
                                <td>
                                    {{{$signature-> comments}}}
                                </td>
                                <td>


                                <?php
                                if (  $GLOBALS['userrole']  == 'admin'){

                                ?>
                                    <ul style="list-style: none; padding-left:0;">
                                        <li>
                                           <a href ="#" class="approveLink" url="{{{"signatures/approve/" .  $signature->signatureid}}}" > Approve </a>

                                        </li>
                                        <li>
                                            <a href ="#" class="denylink" url="{{{"signatures/deny/" .  $signature->signatureid}}}" > Deny </a>
                                        </li>
                                        <!--<li>
                                            <a href ="#" class="sendmessage" url="{{{url("/messages/send/" .  $signature->signatureid)}}}" > Send Message </a>
                                        </li>-->
                                    </ul>
                                    <?php
                                    }
                                ?>
                                </td>
                            </tr>
                            <?php
                            });
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="actionmodal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Complete Action</h4>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label" for="comments">Comments</label>
                                    <textarea class="form-control" id="comments"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="btnSave">Save changes</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->


                    <div class="modal fade" id="sigmodal">
                        <div class="modal-dialog" style="width: 80%;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modalPreview" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Signature Preview</h4>
                                </div>
                                <div class="modal-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class='notifications top-right'></div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('bodyScripts')
    <script src="{{{asset("js/bootstrap-notify.min.js")}}}"></script>
    <script>
        $(document).ready(function() {
            $('#signaturesTable').dataTable();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                }
            });
            //Signature Preview in the table.
            $(".signaturePreview").click(function(){
                var svgdata= $(this).parent('.sPrev').find('label').html();
                console.log(svgdata);
                $('#sigmodal').unbind();
                $('#sigmodal').on('show.bs.modal', function (e) {
                    $(this).find(".modal-body").html(svgdata);
                    $(this).find(".modal-body").find('svg').removeAttr('viewBox');
                });
                $('#sigmodal').modal('show');
            });

            $('.approveLink').click(function(){
		var data; 
		var link = $(this); 
                $('#actionmodal').unbind();
                $('#actionmodal').on('show.bs.modal', function (e) {
                    $(this).find('#btnSave').click(function(){ 
		    if ( $('#comments').val() == "" ){
			data = link.attr('url') ;
		      }
		    else{
		      data  = link.attr('url') + "/" + $("#comments").val() ;
		    }
		    console.log(data);
		    $.ajax({
                            type    :"POST",
                            url     : data,
                          
                            success :function(response){
				console.log(response);
                                if ( response == 0){
                                    $('#actionmodal').modal('hide');
                                    $('#approveLink').hide();
                                    $.notify({
                                        // options
                                        message: 'Signature Approved.'
                                    },{
                                        // settings
                                        type: 'success'
                                    });
                                }
                               location.reload();
                            }
                        });
                    });
                })
                $('#actionmodal').modal('show');

            });

            $('.denylink').click(function(){
 
		var data; 
		var link = $(this); 
                $('#actionmodal').unbind();
                $('#actionmodal').on('show.bs.modal', function (e) {
                    $(this).find('#btnSave').click(function(){
                         if ( $('#comments').val() == "" ){
			data = link.attr('url') ;
		      }
		    else{
		      data  = link.attr('url') + "/" + $("#comments").val() ;
		    }
			$.ajax({
                            type    :"POST",
                            url     :data,
                            dataType:"html",
                            success :function(response){
                                if ( response == 0){
                                    $('#actionmodal').modal('hide');

                                    $('#approveLink').hide();
                                    $.notify({
                                        // options
                                        message: 'Signature Denied.'
                                    },{
                                        // settings
                                        type: 'error'
                                    });
                                    location.reload();
                                }
                            }
                        });
                    });
                })
                $('#actionmodal').modal('show');

            });

        } );


    </script>
@endsection
