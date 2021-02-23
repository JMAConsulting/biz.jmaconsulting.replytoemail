<div class="additional-actions">
<div class="archiveemail" style="float:left">
    {capture assign=archiveUrl}{crmURL p="civicrm/activity/archive" q="action=edit&reset=1&cid=`$contactId`&selectedChild=activity&atype=3&context=archive&activityId=`$activityId`"}{/capture}
    {capture assign=link}class="action-item crm-popup crm-button" style="text-decoration:none;color:#FFF" href="{$archiveUrl}"{/capture}
    {ts 1=$link}<a %1><span><i class="crm-i fa-times"></i> Archive</span></a>{/ts}
</div>
<div class="replytoemail" style="float:left">
    {capture assign=replyUrl}{crmURL p="civicrm/activity/email/add" q="action=add&reset=1&cid=`$contactId`&selectedChild=activity&atype=3&context=sendReply&activityId=`$activityId`"}{/capture}
    {capture assign=link}class="action-item crm-popup crm-button" style="text-decoration:none;color:#FFF" href="{$replyUrl}"{/capture}
    {ts 1=$link}<a %1><span><i class="crm-i fa-paper-plane-o"></i> Reply</span></a>{/ts}
</div>
</div>

{literal}
<script type="text/javascript">
    CRM.$(function($) {
        $('.crm-submit-buttons').append($('.additional-actions'));
    });
</script>
{/literal}