<div class="modal-dialog" role="document">
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title"><?php echo ( $mail->subject ); ?></h4>
        </div>


        <div class="modal-body">                         
            <?php //print_r( $mail ); ?>
                        
            <div class="row">
                <div class="col-md-2">Sender:</div>
                <div class="col-md-10"><?php echo ( $mail->mail_from ); ?></div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-2">To:</div>
                <div class="col-md-10"><?php echo ( $mail->mail_to ); ?></div>
                <div class="clearfix"></div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-2">Message:</div>
                <div class="col-md-10" style="word-break: break-all;"><?php echo ( $mail->body ); ?></div>
                <div class="clearfix"></div>
            </div>
            
            
                                                  
        </div>
        <div class="modal-footer" style="padding: 10px 15px;">
            <button type="button" class="btn btn-default" data-dismiss="modal"><span aria-hidden="true">&times;</span> Close</button>            
        </div>
         
    </div>
</div>

