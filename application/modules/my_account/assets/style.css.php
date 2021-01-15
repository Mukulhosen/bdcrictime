<style type="text/css">
    .login-box-custom {
        background: #ffffff;
        padding: 50px 20px;
        margin: 25px 0;
    }
    .panel-default > .panel-heading {
        background-color: #e2e2e2;
    }
    .login-box a { cursor: pointer; }

    .open-mail{ cursor: pointer;}

    .my_sidebar{
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        border-color: #ddd;
    }
    .my_sidebar .list-group-item {
        border: 1px solid #ddd;
    }


    .tiles .tile {
        padding: 12px 20px;
        background-color: #f8f8f8;
        border-right: 1px solid #ccc;
    }
    .tiles .tile a {
        text-decoration: none;
    }
    .start:hover{
        text-decoration: none;
    }
    .tile .icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 48px;
        line-height: 1;
        color: #ccc;
    }
    .tile .stat {
        margin-top: 20px;
        font-size: 40px;
        line-height: 1;
    }
    .tile .title {
        font-weight: bold;
        color: #888;
        text-transform: uppercase;
        font-size: 12px;
    }
    .tiles .tile .highlight {
        margin-top: 4px;
        height: 2px;
        border-radius: 2px;
    }
    .bg-color-blue {
        background-color: #5bc0de;
        height: 3px;
        margin-top: 13px;
    }
    .bg-color-red {
        background-color: red;
        height: 3px;
        margin-top: 13px;
    }
    .bg-color-green {
        background-color: green;
        height: 3px;
        margin-top: 13px;
    }
    .bg-one{
        background-color: #F5F5F5;
        border-right: 1px solid #999;
        padding: 20px;
    }
    .bg-two{
        background-color: #F5F5F5;
        border-right: 1px solid #999;
        padding: 20px;
    }
    .bg-three{
        background-color: #F5F5F5;
        padding: 20px;
    }



    .mail_sidebar_user{
        height: 600px;
        overflow: auto;
    }
    .mail_sidebar_user_list ,.mail_sidebar_user_list_system {
        border-bottom: 1px solid #eee;
        display: table;
        height: auto;
        margin-bottom: 0;
        min-height: 60px;
        padding-top: 10px;
        padding-bottom: 10px;
        width:100%;
    }
    .mail_sidebar_user_list:hover,.mail_sidebar_user_list_system:hover {
        background: #f5f5f5;
    }
    .selected{
        background: #f5f5f5;
    }
    .custompadd {
        padding-left: 15px;
        padding-right:15px;
    }
    .custompadd .label-primary {
        background-color: #39AA0D;
    }
    .mail_sidebar_user_list h4,.mail_sidebar_user_list_system h4 {
        color: #000;
        font-family: Montserrat;
        font-size: 15px;
        font-weight: 700;
        line-height: 14px;
        margin: 0;
        padding: 0;
    }
    .mail_sidebar_user_list h4 span,.mail_sidebar_user_list_system h4 span {
        color: #8e8e8e;
        float: right;
        font-size: 12px;
        font-weight: 400;
        margin-top: 8px;
    }
    .mail_sidebar_user_list h5,.mail_sidebar_user_list_system h5 {
        color: #434343;
        font-family: Montserrat;
        font-size: 13px;
        line-height: 13px;
        margin-bottom: 6px;
        margin-top: 6px;
    }
    .mail_sidebar_user_list p,.mail_sidebar_user_list_system p {
        color: #8f8f8f;
        font-size: 12px;
        line-height: 12px;
    }
    .mail_body_list {
        border-bottom: 1px solid #eee;
        display: table;
        height: auto;
        margin-bottom: 10px;
        width: 100%;
    }
    .mail_body_list h3 {
        margin-top: 5px;
        padding-left: 10px;
    }
    .mail_body_list h4 {
        color: #434343;
        font-family: Montserrat;
        font-size: 15px;
        font-weight: bold;
        margin: 0;
        padding: 0;
    }
    .mail_body_list h4 span{
        float: right;
        font-size: 10px;
        color: #666;
    }
    .mail_body_list .mail_body p {
        color: #404040;
        font-family: Montserrat;
        font-size: 13px;
        margin-bottom: 5px;
    }
    .massegedate {
        border-bottom: 1px solid #e2e2e2;
        border-top: 1px solid #e2e2e2;
        margin-bottom: 15px;
        padding: 5px 5px 5px 10px;
    }
    .massegesubmit {
        background-color: #39AA0D;
        border-radius: 5px !important;
        font-family: Montserrat;
        font-size: 14px;
        margin-top: 11px;
        padding: 10px 45px;
    }
    .massegesubmit:hover, .massegesubmit:focus, .massegesubmit.focus, .massegesubmit:active, .massegesubmit.active {
        background-color: #39AA0D;
    }
    .mail_body_list .image {
        background-image: url(assets/theme/images/email-images.png);
        background-repeat: no-repeat;
        width: 50px;
        height: 50px;
        float: left;
    }
    .mail_body_list .text {
        background-image: url(assets/theme/images/email-txt.png);
        background-repeat: no-repeat;
        width: 50px;
        height: 50px;
        float: left;
    }
    .mail_body_list .doc {
        background-image: url("assets/theme/images/email-doc.png");
        background-repeat: no-repeat;
        width: 50px;
        height: 50px;
        float: left;
    }
    .mail_body_list .xls {
        background-image: url("assets/theme/images/email-xlx.png");
        background-repeat: no-repeat;
        width: 50px;
        height: 50px;
        float: left;
    }
    .mail_body_list .pdf {
        background-image: url("assets/theme/images/email-pdf.png");
        background-repeat: no-repeat;
        width: 50px;
        height: 50px;
        float: left;
    }
    .mail_body_list .video {
        background-image: url("assets/theme/images/email-vedio.png");
        background-repeat: no-repeat;
        width: 50px;
        height: 50px;
        float: left;
    }
    .mail_body_list .zip {
        background-image: url("assets/theme/images/email-zip.png");
        background-repeat: no-repeat;
        width: 50px;
        height: 50px;
        float: left;

    }
    .file_name {
        float: left;
        padding-top:10px;
    }
    #search .form-control {
        border: 2px solid #39AA0D;
        border-radius: 0;
        margin-bottom:10px;
    }

    #ajax_respond_search .ajax_notice {
        margin-left: 15px;
        margin-top: 42px;
        width: 89.5% !important;
    }
    .k_new .nav > li > a {
        padding: 10px;
        border-radius:0;
    }
    .k_new .nav > li > a {

    }
    .massagesubmit { padding-left:0;}
    .massagesubmit .btn {
        background-color: #39AA0D;
        border-radius: 5px !important;
        padding: 10px 38px;
    }
    .mbform {
        margin-bottom: 12px;
    }
    .mbform img {
        height: 180px !important;
        width: 100% !important;
    }
    .mbform span {
        color: #39AA0D;
    }
    .mbform p {
        font-size: 15px;
        font-weight: 600;
    }
    #reply label {
        margin-bottom: 7px;
    }

    #reply .btn.btn-primary.btn-sm.add_file {
        background-color: #0f74de;
        padding: 8px 10px;
    }
    .kamransss .mail_body {
        min-height: 70px !important;
    }
    .attachments_download {
        padding-left: 21px;
        margin-bottom: 10px;
    }
    .attachments_show {
        padding-left: 12px;
        margin-bottom: 7px;
    }

    .show_message_list {
        display: none;
        margin-right: 12px;
        margin-bottom: 10px;
    }
    .reply_bar {
        display: block;
    }
    
    
    
    
    
    
    /*Chat new design dextop*/


    .col-md-3.chatlogo {
        text-align: center;
    }

    .chatdate {
        margin-top: 5px;
    }
    .chatlogo { text-align: center; }
.chatlogo img {
    width: 100px;
    height: 100px;

}
.typemessage {
    margin-top: 15px;
}
.chatbox {
    margin-top: 5px;
    margin-bottom: 5px;
}
    .chatbox p {
        background: #e1ffc7;
        padding: 20px 10px;
        font-size: 14px;
        border-radius: 6px;
    }
    .chatbox p:before {
        left: -3px;
        top: 50px;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-color: rgba(225, 255, 199, 0);
        border-right-color: #e1ffc7;
        border-width: 10px;
        margin-top: -30px;
    }
    .chatbox.chat2nd p {
        background: #c7edfc;
    }
    .chatbox.chat2nd p:before {
        left: auto;
        right: -3px;
        top: 50px;
        border-left-color: #c7edfc;
        border-right-color: transparent;
    }
    .typemessage .input-group {
        width: 100%;
    }
    .typemessage textarea.btn-input {
        padding: 10px;
        border-radius: 5px !important;
        font-size: 14px;
        resize: none;
        overflow: hidden;
    }
    .typemessage span.input-group-btn {
        position: absolute;
        right: 50px;
        top: 8px;
        z-index: 99999;
    }
    button#btn-chat.sendchat {
        background-color: #3dc453;
        border-radius: 100%;
        width: 40px;
        height: 40px;
        border-color: transparent;
        margin-top: 14px;
    }
    .sendchat i.fa.fa-location-arrow {
        transform: rotate(45deg);
        font-size: 19px;
    }
    .chatlogo img {

        border-radius: 100%;
    }
    .badge sup {
    color: #fff;
    top: 0;
    font-size: 12px;
}

    /*Chat new design dextop end*/
    
    
    
      
    
    
    
    

    @media only screen and (max-width: 767px) {
       .mypostmob a.btn.btn-primary.btn-xs {
    margin-bottom: 10px;
}
        .mypostmob{overflow-x: scroll;}
        .show_message_list {
            display: block;
        }
        .reply_bar {
            display: none;
        }

        .mail_sidebar_user_list, .mail_sidebar_user_list_system {
            margin: 0 auto;}
        .my_account.k_new .panel.panel-default.no-padding {
            border-radius: 0;
            display: block !important;
        }
        .formobilemassv {
            padding: 0;
        }
        .mail_sidebar_user {
            height: 410px;
        }
        .mail_sidebar_user_list .img-circle, .mail_sidebar_user_list_system  .img-circle {

        }
        .col-md-3.no-padding.newdesignmailthumb {
            width: 20%;
            float: left;
        }
        .col-md-9.no-padding.newdesignmail { width: 79% ; float: left; padding-top: 15px;}
        .formobilemassv .img-circle {

            display: none;
        }
        .mail_body_list h4 {
            padding: 0 10px 0 18px;
        }
        .frontedattachment {
            padding: 0;
        }
        .frontedattachment .col-md-2 {
            padding-left: 0;
        }
        .frontedattachment .col-md-5 {
            margin-bottom: 6px;
            padding-left: 0;
        }
        .fromyaccountp {
            display: block !important;
        }
        .fromyaccountpa {
            overflow-x: scroll;
        }
        .k_new .aboutbanner-text h2 {
            font-size: 23px !important;
        }
        .mbform .col-md-6 {
            margin-bottom: 12px;
            padding-left: 0px !important;
        }
        .force_mobile_width {
            width: 100%;
        }
        .user_welcome {
            font-size: 25px;
            padding-bottom: 25px;
        }
        
    .new-chat-design .col-md-10 {
        width: 75%;
        float: left;
    }
    .col-md-2.chatlogo {
        text-align: center;
    }
    .new-chat-design .col-md-2 {
        width: 25%;
        float: left;
        padding-right: 0;
    }
     .new-chat-design .chat2nd .col-md-2  {padding-right: 15px; padding-left: 0;}
        .chatlogo img {
            width: 60px;
            height: 60px;
            border-radius: 100%;
        }
        .chatbox {
            margin-bottom: 16px;
        }
        .chatbox p {
            background: #e1ffc7;
            padding: 20px 10px;
            font-size: 14px;
            border-radius: 6px;
        }
        .chatbox.chat2nd p {
            background: #c7edfc;

        }
        .chatbox p:before {
            left: -3px;
            top: 50px;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-color: rgba(225, 255, 199, 0);
            border-right-color: #e1ffc7;
            border-width: 10px;
            margin-top: -30px;
        }
        .chatbox.chat2nd  p:before {
            left: auto;
            right: -3px;
            top: 50px;
            border-left-color: #c7edfc;
            border-right-color: transparent;
        }
        .chatdate {
            text-align: center;
            margin-top: 5px;
        }
        .col-md-3.chatlogo {
            text-align: center;
        }
        button#btn-chat.sendchat {
            background-color: #3dc453;
            border-radius: 100%;
            width: 40px;
            height: 40px;
            border-color: transparent;
        }
        button#btn-chat.sendchat:focus {outline: none; border:0;}
        .typemessage span.input-group-btn {
            position: absolute;
            right: 50px;
            top: 8px;
            z-index: 99999;
        }
        .typemessage .input-group {
            width: 100%;
        }
        .typemessage textarea.btn-input {
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            resize: none;
            overflow: hidden;
        }
        .sendchat i.fa.fa-location-arrow {
            transform: rotate(45deg);
            font-size: 19px;
        }
    }

    #respond p {
        text-align: center;
        padding: 10px;
        border-radius: 5px;
        color: #fff;
    }
    #respond p.ajax_error{
        background: #dc3545 !important;
    }
    #respond p.ajax_success{
        background: #28a745 !important;
    }
    #respond p.ajax_processing{
        background: #17a2b8 !important;
    }
</style>