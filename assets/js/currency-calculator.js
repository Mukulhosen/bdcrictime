var last_change='';$(window).ready(function(e){ajaxCalculator()});$('#currency_mp, #currency_bc_select, #currency_cc_select').on('change',function(){ajaxCalculator()});$('#currency_bc_input').on('keyup',function(){last_change='bc';ajaxCalculator()});$('#currency_cc_input').on('keyup',function(){last_change='cc';ajaxCalculator()});function ajaxCalculator(){$.ajax({type:"POST",url:"ajax/currency_calculator",dataType:'json',data:{'mp':$('#currency_mp').children("option:selected").val(),'bc_select':$('#currency_bc_select').children("option:selected").val(),'bc_input':$('#currency_bc_input').val(),'cc_select':$('#currency_cc_select').children("option:selected").val(),'cc_input':$('#currency_cc_input').val(),'last_change':last_change},success:function(data){$("#currency_bc_text").text(data.bc_text);$("#currency_cc_text").text(data.cc_text);if(last_change=='bc'){$("#currency_cc_input").val(data.value)}else if(last_change=='cc'){$("#currency_bc_input").val(data.value)}}})}