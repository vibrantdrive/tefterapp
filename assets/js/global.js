$(function(){$("body").addClass("hasJS");$("A[rel='external']").click(function(){window.open($(this).attr("href"));return false});setTimeout("hideSuccessMessages()",5000)});function hideSuccessMessages(){$(".success").slideUp("fast",function(){$(this).remove()})}$("#type").change(function(){$("#loading").removeClass("hide");var d=$("#base_url").val();var a=$("#call_script").val();var e=$(this).val();var b=0;var c=$("#mode").val();if(c=="edit"){b=$("#account_id").val()}$("#account_type_fields").slideUp("fast");$("#account_type_fields").empty();if(e!=""){$.ajax({type:"POST",url:d+a,dataType:"json",data:"id="+e+"&mode="+c+"&account_id="+b,failure:function(f){$("#loading").addClass("hide")},success:function(f){if(f.success){if(f.code==200){$("#account_type_fields").append(f.data);$("#account_type_fields").slideDown("fast");$(".more-options").click(function(){if($(this).is(":hidden")){$(this).toggleClass("opened")}else{$(this).toggleClass("opened");$(".type_ftp_options").slideToggle("fast")}return false})}$("#loading").addClass("hide")}}})}else{$("#loading").hide()}});$("#show-more").click(function(){showMore();return false});$("#user_role").change(function(){if($(this).val()=="2"){$(".user-access").slideDown("fast")}else{$(".user-access").slideUp("fast")}});$("#select_all").click(function(){var a=this.checked;$("input[class=selector]").each(function(){this.checked=a})});$("#select_all_accounts").click(function(){var a=this.checked;$("input[class=account_selector]").each(function(){this.checked=a})});$("#email_protocol").change(function(){if($(this).val()=="smtp"){$(".smtp").slideDown("fast")}else{$(".smtp").slideUp("fast")}});$("#accounts .aside-link").click(function(){$("#message_second").hide();$.blockUI({message:$("#add-new-client")});$("#add-new-client .cancel").click(function(){$.unblockUI({onUnblock:function(){$("#new_client").attr("value","");$(".success").hide();$(".problem").hide()}});return false});return false});$("#users .aside-link").click(function(){$("#message_second").hide();$.blockUI({message:$("#add-new-client")});$("#add-new-client .cancel").click(function(){$.unblockUI();return false});return false});$('li[id^="letter_"]').click(function(){var b=$("#"+this.id).hasClass("clicked");if(!b){$("#"+this.id).addClass("clicked");resetFilters();resetLimitCounters();resetAllLetters();$("#"+this.id).addClass("selected");var d=this.id.indexOf("_");var c=this.id.substring(d+1);if(c=="all"){c=""}switch($("#call_script_table").val()){case"accounts/table":var a=document.getElementById("member");if(a){loadTable(c,"","","",true,"no")}else{loadTable(c,"","","",true,"yes")}break;case"clients/table":loadClientTable(c,"",true);break;case"users/table":loadUserTable(c,"",true);break;case"groups/table":loadGroupTable(c,"",true);break}}return false});$("form").submit(function(){$("button[type=submit]",this).attr("disabled","disabled")});