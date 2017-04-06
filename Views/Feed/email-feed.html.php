<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<?php
/** @var \FeedIo\Feed[] $feed */
$feeds;
?>
<title>Bloggr</title>

<!-- Wrapper 5 (3 Col From Left to Right) -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" >
	<tr>
		<td width="100%" valign="top">
		
			
			<!-- Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
				<tr>
					<td>
						
						<!-- Wrapper -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%">
									
									<!-- Text -->
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
										<tr>
											<td width="100%" height="30"></td>
										</tr>
										<tr>
											<td width="100%" style="font-size: 23px; color: rgb(255, 83, 53); text-align: left; font-family: proxima_nova_rgregular, Helvetica, Arial, sans-serif; line-height: 32px; vertical-align: top; "class="textCenter" >
                                                <?php $primeiroFeed = array_shift($feeds); ?>
												<p ><?= $primeiroFeed->getTitle(); ?></p>
											</td>
										</tr>
										<tr>
											<td width="100%" height="20"></td>
										</tr>
										<tr>
                                            <?php
                                                $conteudoPrincipal = $primeiroFeed->getDescription();
                                                $stringLength = strlen($conteudoPrincipal);
                                                $conteudoPrincipal = substr($conteudoPrincipal, 0, $stringLength/2);
                                            ?>
											<td width="100%" style="font-size: 14px; color: rgb(150, 150, 150); text-align: left; font-family: proxima_nova_rgregular, Helvetica, Arial, sans-serif; line-height: 24px; vertical-align: top; "class="textCenter" >	
												<p><?= $conteudoPrincipal . "..."; ?></p>
											</td>
										</tr>
										<tr>
											<td width="100%" height="30"></td>
										</tr>
										<tr>
											<td valign="middle" width="100%">
												<table border="0" cellpadding="0" cellspacing="0" align="left">
                                                    <table border="0" cellpadding="0" cellspacing="0" align="left">
                                                        <tr>
                                                            <td align="center" height="36" bgcolor="#ff5335"style="color: rgb(255, 255, 255); font-family: proxima_nova_rgbold, 'Myriad Pro', Helvetica, Arial, sans-serif; font-size: 12px; background-color: rgb(255, 83, 53); display: block" class="buttonPad" >
                                                                <a href="<?= $primeiroFeed->getLink(); ?>" style="color: #ffffff; font-size:12px; text-decoration: none; line-height:20px; width:100%; display:inline-block; text-transform: uppercase; padding-top: 7px; padding-bottom: 7px;"class="hover"  ><span class="erase">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>Leia Mais<span class="erase">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>
                                                            </td>
                                                        </tr>
                                                    </table>
												</table>
											</td>
										</tr>
										<tr>
											<td width="100%" height="20"></td>
										</tr>
									</table>
									
								</td>
							</tr>
						</table><!-- End Wrapper 2 -->
						
						<!-- Space -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
							<tr>
								<td width="100%" height="25"></td>
							</tr>
						</table><!-- End Space -->
						
					</td>
				</tr>
			</table><!-- End Wrapper -->
		
		
		</td>
	</tr>
</table><!-- Wrapper 5 -->

<!-- Wrapper 3 (Divider) -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" >
	<tr>
		<td width="100%" valign="top">
		
		
			<!-- Wrapper -->
			<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
				<tr>
					<td width="100%">
									
						<!-- Divider -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%" height="1" bgcolor="#f1f1f1" style="font-size: 1px; line-height: 1px;"></td>
							</tr>
						</table><!-- End Divider -->
									
					</td>
				</tr>
			</table><!-- End Wrapper 2 -->
						
			
		</td>
	</tr>
</table><!-- End Wrapper 3 -->

<!-- Wrapper 2 (3 Column Testimonials) -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" >
	<tr>
		<td width="100%" valign="top">
			

			<!-- Wrapper -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
				<tr>
					<td>
								
						<!-- Wrapper -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
							<tr>
								<td width="100%">
									
									<!-- Headline -->
									<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
										<tr>
											<td width="100%" style="font-size: 30px; color: rgb(58, 66, 76); text-align: left; font-family: proxima_novablack, Helvetica, Arial, sans-serif; line-height: 24px; vertical-align: top; " class="fullCenter">	
												<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
													<tr>
														<td width="100%" style="font-size: 30px; color: rgb(58, 66, 76); text-align: left; font-family: proxima_novablack, Helvetica, Arial, sans-serif; line-height: 34px; vertical-align: top; ">
															<p>Artigos Relacionados:</p>
														</td>
													</tr>
												</table>				
											</td>
										</tr>
										<tr>
											<td width="100%" height="40"></td>
										</tr>
									</table><!-- End Headline -->

                                    <?php foreach($feeds as $feed): ?>
                                        <table width="260" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
                                            <tr>
                                                <td width="100%" valign="top">
                                                    <table width="260" border="0" cellpadding="0" cellspacing="0" align="center" class="fullCenter">
                                                        <tr>
                                                            <td width="100%" style="font-size: 23px; text-align: left; font-family: proxima_nova_rgregular, Helvetica, Arial, sans-serif; line-height: 30px; vertical-align: top; color: rgb(255, 83, 53); ">
                                                                <p><?= $feed->getTitle(); ?></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" height="20"></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" style="font-size: 14px; color: rgb(150, 150, 150); text-align: left; font-family: proxima_nova_rgregular, Helvetica, Arial, sans-serif; line-height: 24px; vertical-align: top; ">
                                                                <?php
                                                                $segundoConteudo = $feed->getDescription();
                                                                $stringLength = strlen($segundoConteudo);
                                                                $segundoConteudo = substr($segundoConteudo, 0, $stringLength/2);
                                                                ?>
                                                                <p><?= $segundoConteudo . "..." ?></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" height="35">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle" width="100%">
                                                                <table border="0" cellpadding="0" cellspacing="0" align="left">
                                                                    <tr>
                                                                        <td align="center" height="36" bgcolor="#ff5335"style="color: rgb(255, 255, 255); font-family: proxima_nova_rgbold, 'Myriad Pro', Helvetica, Arial, sans-serif; font-size: 12px; background-color: rgb(255, 83, 53); display: block" class="buttonPad" >
                                                                            <a href="<?= $feed->getLink(); ?>" style="color: #ffffff; font-size:12px; text-decoration: none; line-height:20px; width:100%; display:inline-block; text-transform: uppercase; padding-top: 7px; padding-bottom: 7px;"class="hover"  ><span class="erase">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>Leia Mais<span class="erase">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" height="10">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    <?php endforeach; ?>
									<!-- Col Left -->
									<!-- Space -->
									<table width="1" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">
										<tr>
											<td width="100%" height="30">									
											</td>
										</tr>
									</table>
																
								</td>
							</tr>
						</table><!-- End Wrapper -->
						
						<!-- Space -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
							<tr>
								<td width="100%" height="25"></td>
							</tr>
						</table><!-- End Space -->
					</td>
				</tr>
			</table><!-- Nav Wrapper -->
		
		
		</td>
	</tr>
</table><!-- Wrapper 2 -->

<!-- Wrapper 2 (Unsubscribe) -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" >
	<tr>
		<td width="100%" valign="top" bgcolor="#1d181f"style="background-color: rgb(29, 24, 31); ">
			
			<!-- Unsubscribe -->
			<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
				<tr>
					<td width="100%" height="13"></td>
				</tr>
				<tr>
					<td width="100%">
					
						<!-- Unsubscribe -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
							<tr>
								<td width="100%" style="font-size: 14px; color: rgb(255, 83, 53); text-align: center; font-family: proxima_nova_rgbold, Helvetica, Arial, sans-serif; line-height: 22px; vertical-align: top; " class="fullCenter">
									 <!--subscribe--><a href="#" style="color: rgb(255, 83, 53); " >Unsubscribe</a><!--unsub-->
								</td>
							</tr>
						</table>
														
					</td>
				</tr>
			</table><!-- End Unsubscribe -->
			
			<!-- Space -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
				<tr>
					<td width="100%" height="8">									
					</td>
				</tr>
			</table><!-- End Space -->
		
			
		</td>
	</tr>
</table><!-- End Wrapper 2 -->

<!-- Wrapper 2 (Copyright) -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" >
	<tr>
		<td width="100%" valign="top" bgcolor="#1d181f"style="background-color: rgb(29, 24, 31); ">
			
			<!-- Unsubscribe -->
			<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
				<tr>
					<td width="100%" height="10"></td>
				</tr>
				<tr>
					<td width="100%">
					
						<!-- Copyright -->
						<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
							<tr>
								<td width="100%" style="font-size: 14px; color: rgb(255, 255, 255); text-align: center; font-family: proxima_nova_rgregular, Helvetica, Arial, sans-serif; line-height: 22px; vertical-align: top; " class="fullCenter">	
									<p >© Bannet 2017 All rights Reserved</p>
								</td>
							</tr>
						</table>
														
					</td>
				</tr>
			</table><!-- End Copyright -->
			
			<!-- Space -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
				<tr>
					<td width="100%" height="10">									
					</td>
				</tr>
			</table><!-- End Space -->
		
			
		</td>
	</tr>
</table><!-- End Wrapper 2 -->

<!-- Wrapper 2 (Space) -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" >
	<tr>
		<td width="100%" valign="top" bgcolor="#1d181f"style="background-color: rgb(29, 24, 31); ">
			
			<!-- Space -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
				<tr>
					<td width="100%" height="40">									
					</td>
				</tr>
				<tr>
					<td width="100%" height="1">									
					</td>
				</tr>
			</table><!-- End Space -->
		
			
		</td>
	</tr>
</table><!-- End Wrapper 2 -->
	<style>body{ background: none !important; } </style>