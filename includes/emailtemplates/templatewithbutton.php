<?php
$year = date("Y");
$singleQuotes = "'";
$templateWithButton = '
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<!--[if !mso]><!-->
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!--<![endif]-->
<meta name="format-detection" content="telephone=no" />
<title>[title]</title>
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i,900" rel="stylesheet" />  
<!--<![endif]--> 
<style type="text/css">
body {-webkit-text-size-adjust: 100% !important;-ms-text-size-adjust: 100% !important;-webkit-font-smoothing: antialiased !important;width: 100%;height: 100%;
	background-color: rgb(231, 240, 247);	margin: 0;	padding: 0;	-webkit-font-smoothing: antialiased;}
img { border: 0 !important; outline: none !important; }
p { Margin: 0px !important; Padding: 0px !important; }
table { border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px; }
td, a, span { border-collapse: collapse; mso-line-height-rule: exactly; }
.ExternalClass * { line-height: 100%; }
span.MsoHyperlink { mso-style-priority: 99; color: inherit; }
span.MsoHyperlinkFollowed { mso-style-priority: 99; color: inherit; }
.em_defaultlink a { color: inherit !important; text-decoration: none !important; }
.rw_phone_layout .em_full_img{width:100%; height:auto!important;}
.rw_tablet_layout .em_full_img{width:100%; height:auto!important;} 
</style>
<!-- @media only screen and (max-width: 640px) 
		   {
		   -->
<style type="text/css">
@media only screen and (max-width: 640px) {
td[class=em_h1] { height:60px !important;  font-size:1px !important;  line-height:1px !important;}
table[class=myfull] {width:100% !important; max-width:300px!important; text-align:center!important;}
table[class=notify-5-wrap] {width:100% !important; max-width:400px;}
table[class=full] { width:100% !important;}
td[class=fullCenter] { width:100% !important; text-align:center!important}
td[class=em_hide] {display:none !important;}
table[class=em_hide] {display:none !important;}
span[class=em_hide] {display:none !important;}
br[class=em_hide] {display:none !important;}
img[class=em_full_img] {width:100% !important; height:auto !important;}
img[class="em_logo"] {text-align:center;}
td[class=em_center] {text-align:center !important;}
table[class=em_center] {text-align:center !important;}
td[class=em_h20] {height:20px !important;} 
td[class=em_h30] { height:30px !important;}
td[class=em_h40] { height:40px !important;}
td[class=em_h50] { height:50px !important;} 
td[class=em_pad] { padding-left:15px !important; padding-right:15px !important;} 
td[class=em_pad2] { padding-left:25px !important; padding-right:25px !important;} 
img[class=img125] { max-width:125px;}
table[class=small-center] { max-width:350px!important; text-align:center!important;}
td[class=em_autoHeight] {height:auto!important;}
td[class=winebg] { background:#b92547; -webkit-border-top-right-radius:5px!important; -moz-border-radius-topright:5px!important; border-top-right-radius:5px!important; -webkit-border-bottom-left-radius:0!important;-moz-border-radius-bottomleft:0!important; border-bottom-left-radius:0!important;}
td[class=myHeading]{font-size:24px!important; text-align:center!important; }
td[class=heading]{font-size:28px!important; text-align:center!important;line-height:35px; }
}
</style>
<!-- @media only screen and (max-width: 479px) 
		   {
		   -->
<style type="text/css">
@media only screen and (max-width: 479px)  {
table[class=full] {width:100% !important; max-width:100%!important;}
table[class=myfull] { width:100% !important;}
table[class=notify-5-wrap] {width:100% !important;}
table[class=em_wrapper] {width:100% !important;}
td[class=fullCenter] { width:100% !important; text-align:center!important}
td[class=em_aside] {width:10px !important;}
td[class=em_hide] { display:none !important;}
table[class=em_hide] {display:none !important;}
span[class=em_hide] {display:none !important;}
br[class=em_hide] {display:none !important;}
img[class=em_full_img] {width:100% !important;height:auto !important;}
img[class="em_logo"] {text-align:center;}
td[class=em_center] {text-align:center !important;}
table[class=em_center] {text-align:center !important;}
td[class=em_h20] {height:20px !important;}  
td[class=em_h30] {height:30px !important;}
td[class=em_h40] {height:40px !important;}
td[class=em_h50] {height:50px !important;} 
td[class=em_pad] {padding-left:10px !important;padding-right:10px !important;} 
td[class=em_pad2] {padding-left:20px !important;padding-right:20px !important;} 
table[class=em_btn] {width:130px !important;}
td[class=em_btn_text] {font-size:10px !important;height:26px !important;}
a[class=em_btn_text] {line-height:26px !important;}
td[class=em_h1] {height:60px !important;font-size:1px !important;line-height:1px !important;}
td[class=em_bg] {background:none !important;}
img[class=img125] {max-width:110px;height:auto!important;}
table[class=small-center] {max-width:100%!important;text-align:center!important;}
td[class=em_autoHeight] {height:auto!important;}
td[class=myHeading]{font-size:24px!important; text-align:center!important; color:#ff0000}
td[class=heading]{font-size:26px!important; text-align:center!important;line-height:35px; }
td[class=winebg] {background:#b92547; -webkit-border-top-right-radius:5px!important; -moz-border-radius-topright:5px!important; border-top-right-radius:5px!important; -webkit-border-bottom-left-radius:0!important;-moz-border-radius-bottomleft:0!important; border-bottom-left-radius:0!important;}
 
}
</style>
 
<!--[if mso]>
<style type="text/css">
body {
   font-family:arial, helvetica, sans-serif !important;
}

table {
   font-family:arial, helvetica, sans-serif !important;
}

td {
   font-family:arial, helvetica, sans-serif !important;
}

</style>
<![endif]-->
 

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!--Full width table start-->
<div id="sort_them"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td align="center" valign="top" ><div>
          <table align="center" class="em_main_table" width="500" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed; " >
            <tr>
              <td class="em_hide" style="line-height:1px; font-size:1px;" width="500"><img src="[siteurl]/assets/images/email/spacer.gif" height="1"  width="500" style="max-height:1px; min-height:1px; display:block; width:500px; min-width:500px;" border="0" /></td>
            </tr>               
          </table>
        </div></td>
    </tr>     
  </table>
 <!-- Notify 9-->   
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module">
	<tr mc:repeatable>
		<td bgcolor="#e7f0f7" align="center">			
			<!-- Mobile Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full"  >
				<tr>
					<td width="100%" align="center">
					
						
						<div class="sortable_inner">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                            <tr>
                                 <td height="60" class="em_h1">&nbsp;</td>
                            </tr>               
                        </table>  
						
						<table width="400" border="0" cellpadding="0" cellspacing="0" align="center" class="full" object="drag-module-small">
							<tr>
								<td align="center" width="400" valign="middle">
									
									<!-- Header Text --> 
									<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
										<tr>
											<td valign="middle" width="100%" style="text-align: center;" class="fullCenter" object="image-editable">
											<a href="#" target="_blank" style="text-decoration:none;"><img class="em_logo" src="[logo]" alt="logo" width="160" height="45" border="0" style="text-align:center;" /></a>
											</td>
										</tr>
									</table>							
								</td>
							</tr>
						</table>
					 
						<table width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
                            <tr>
                                 <td height="40" class="em_h40">&nbsp;</td>
                            </tr>               
                        </table> 
						
						</div>
					</td>
				</tr>
			</table>
			
			<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full" style="max-width:600px!important;" >
				<tr>
				 
					<td align="center" width="100%" valign="middle" class="em_pad2">			
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
							<tr>
								<td align="center" width="100%" valign="middle" bgcolor="#ffffff" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;">
								
							<div class="sortable_inner">
							 	
								<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
									<tr>
									  <td height="40" class="em_h30"><img src="[siteurl]/assets/images/email/spacer.gif" width="1" alt="" height="1" border="0" style="display:block;" /></td>
									</tr>
								</table>
                                
                                <table align="center" width="500" border="0" class="full" cellspacing="0" cellpadding="0" object="drag-module-small">
									<tr>
									  <td align="center" class="em_pad">
                                     
                                     
                                     
                              <!--[if (gte mso 9)|(IE)]>
<table width="500" align="center" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="135" align="center">
            <![endif]-->
									  
										<table align="left" width="135"  class="full" border="0" cellspacing="0" cellpadding="0">
										<tr>
									  <td height="15" ><img src="[siteurl]/assets/images/email/spacer.gif" width="1" alt="" height="1" border="0" style="display:block;" /></td>
									</tr>
										<tr>
										<td class="em_center">
											 <img src="[avatar]" width="100" height="100" alt="" align="center" border="0" style="height:auto;" />
										</td>
										
										</tr>
										</table>		
										
							  <!--[if (gte mso 9)|(IE)]>
        </td>
		 
		<td width="360" align="right">
<![endif]-->			
										
						<table align="left" width="360" class="full" border="0" cellspacing="0" cellpadding="0"  >

							<tr>
							 
							  <td valign="top" width="100%" class="em_pad">
							  <div class="shortable_inner">
							  
							  
							 <table align="" width="100%" class="" border="0" cellspacing="0" cellpadding="0" object="drag-module-small" >
							 <tr>
								  <td valign="top"  height="1"  style="font-family:'.$singleQuotes.'Lato'.$singleQuotes.', Arial, sans-serif; font-weight:400; font-size:1px;  line-height:1px;" class="em_h30" >&nbsp;
											 
								  </td>
								</tr>
								<tr>
								  <td valign="top" align="left" style="font-family:'.$singleQuotes.'Lato'.$singleQuotes.', Arial, sans-serif; font-weight:400; font-size:28px; color:#000000; line-height:36px;" class="fullCenter" >
									[title]		
								  </td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" class="full" cellpadding="0" object="drag-module-small">
								<tr>
									 <td height="30" class="em_h40">&nbsp;</td>
								</tr>               
							</table>
							  
							 <table   width="100%"  class="full" border="0" cellspacing="0" cellpadding="0" object="drag-module-small" >
								<tr>
								  <td valign="top" align="left" style="font-family:'.$singleQuotes.'Lato'.$singleQuotes.', Arial, sans-serif; font-weight:400; font-size:15px; color:#ababab; line-height:25px;" class="em_center" >
									[note]	
								  </td>
								</tr>
							</table>
							
							<table width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
								<tr>
									 <td height="30" class="em_h40">&nbsp;</td>
								</tr>               
							</table> 	
<!-- Centered Button -->
                            
                            <table border="0" cellpadding="0" cellspacing="0" align="left" class="full" object="drag-module-small">
                              <tr>
                                <td width="100%" align="center"><table border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="mcenter">
                                    <tr>
                                      <td width="100%" align="center"><!-- SORTABLE -->
                                        
                                        <div class="sortable_inner">
                                          <table border="0" cellpadding="0" cellspacing="0" align="left" class="mcenter" >
                                            <tr> 
                                              <td align="center" valign="middle" height="36" bgcolor="#3DA4FF" style="font-family:'.$singleQuotes.'Open Sans'.$singleQuotes.', Arial, sans-serif; font-size:14px; color:#ffffff; padding-left:20px; padding-right:20px; font-weight:400;"  >
                                          <a href="[buttonlink]" target="_blank" style="text-decoration:none; color:#ffffff; line-height:36px; display:block;" object="link-editable" > [buttontext] </a>
                                          </td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                  </table></td>
                              </tr>
                            </table>
<!-- End Button -->			

							<table width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
								<tr>
									 <td height="48" class="em_h40"><img src="[siteurl]/assets/images/email/spacer.gif" width="1" alt="" height="1" border="0" style="display:block;" /></td>
								</tr>               
							</table> 			
								</div>
                                
							  </td>
                             
                              
							</tr>
						</table>
						
						  <!--[if (gte mso 9)|(IE)]>
        </td>
		</tr>
		</table>
<![endif]-->
                                     
                                     
                                      </td>
									</tr>
								</table>
							 
						 
							
							</div>	
									
								</td>
							</tr>
						</table>
						
					</td>
				 
				</tr>
			</table>
		 <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
					<tr>
					  <td height="40" class="em_h40"><img src="[siteurl]/assets/images/email/spacer.gif" width="1" alt="" height="1" border="0" style="display:block;" /></td>
					</tr>
				</table>
			
		 
	
      
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" object="drag-module">
  <tr mc:repetable>
    <td align="center" valign="top" >
	  <!-- SORTABLE -->
		
			<table align="center" class="full" width="400" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;" >
			 <tr>
			  <td>
			  <div class="sortable_inner">
			 				 
				<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
					<tr>
					  <td valign="top" align="center" style="font-family:'.$singleQuotes.'Lato'.$singleQuotes.', Arial, sans-serif; font-weight:400; font-size:11px; color:#777777; line-height:22px;" >
					  &copy; '.$year.' [systemname]. All Rights Reserved.
					  </td>
					</tr>
				</table>
				<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" object="drag-module-small">
					<tr>
					  <td height="73" class="em_h1"><img src="[siteurl]/assets/images/email/spacer.gif" width="1" alt="" height="1" border="0" style="display:block;" /></td>
					</tr>
				</table>
				</div>
					</td>
				</tr>
			</table>
		
	</td>
 </tr>
</table>   
 
 
	</td>
	</tr>
</table>
 <!-- End of Notify 9--> 
 


</div>
 </body></html>';

