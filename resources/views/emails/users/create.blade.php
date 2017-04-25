<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
			<title>Eurosport</title>
		</head>
		<body>
			<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
				<tbody>
					<tr>
						<td>
							<table border="0" cellpadding="0" style="border-collapse:collapse; margin:0 auto; width:600px">
								<tbody>
									<tr>
										<td>
											<table style="border-collapse:collapse; color:#1e2a48; margin:0 auto; width:100%">
												<tbody>
													<tr>
														<td>&nbsp;</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" style="width:100%">
												<tbody>
													<tr>
														<td>
															<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
																<tbody>
																	<tr>
																		<td style="background-color:#F4F2F2; width:600px">
																			<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
																				<tbody>
																					<tr>
																						<td>
																							<img alt="Logo" 
																							src="/assets/img/logo-desk.svg" style="border-style:solid; border-width:0px; display:block; height:80px; width:250px" />
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<img src="http://imgur.com/1axpouk.png" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" style="width:100%">
												<tbody>
													<tr>
														<td>
															<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
																<tbody>
																	<tr>
																		<td>
																			<p>Hi {{ $email_details['name'] }},</p>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<p>Your Eurosport account has been created. Please click the link below and set your password to finalise your account set up.</p>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<p>
																				<a href="@php echo url('user/setpassword/'.$email_details['token']) @endphp" style="color: #0069ac;text-decoration: none;">@php echo url('user/setpassword/'.$email_details['token']) @endphp
																				</a>
																			</p>
																		</td>
																	</tr>
																	{{-- <tr>
																		<td>
																			<p>If you experience any problems setting your password, please contact us on 
																				<strong>01883 772929</strong> or email 
																				<a href="mailto:enquiries@myclubbetting.com" style="color: #0069ac;text-decoration: none;">enquiries@myclubbetting.com</a>.
																			</p>
																		</td>
																	</tr> --}}
																	<tr>
																		<td>&nbsp;</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<p>Copyright 2017 Euro-Sportring. All rights reserved. Developer by aecor.</p>															
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</body>
	</html>