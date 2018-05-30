/*!
 *  Lang.js for Laravel localization in JavaScript.
 *
 *  @version 1.1.10
 *  @license MIT https://github.com/rmariuzzo/Lang.js/blob/master/LICENSE
 *  @site    https://github.com/rmariuzzo/Lang.js
 *  @author  Rubens Mariuzzo <rubens@mariuzzo.com>
 */
(function(root,factory){"use strict";if(typeof define==="function"&&define.amd){define([],factory)}else if(typeof exports==="object"){module.exports=factory()}else{root.Lang=factory()}})(this,function(){"use strict";function inferLocale(){if(typeof document!=="undefined"&&document.documentElement){return document.documentElement.lang}}function convertNumber(str){if(str==="-Inf"){return-Infinity}else if(str==="+Inf"||str==="Inf"||str==="*"){return Infinity}return parseInt(str,10)}var intervalRegexp=/^({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])$/;var anyIntervalRegexp=/({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])/;var defaults={locale:"en"};var Lang=function(options){options=options||{};this.locale=options.locale||inferLocale()||defaults.locale;this.fallback=options.fallback;this.messages=options.messages};Lang.prototype.setMessages=function(messages){this.messages=messages};Lang.prototype.getLocale=function(){return this.locale||this.fallback};Lang.prototype.setLocale=function(locale){this.locale=locale};Lang.prototype.getFallback=function(){return this.fallback};Lang.prototype.setFallback=function(fallback){this.fallback=fallback};Lang.prototype.has=function(key,locale){if(typeof key!=="string"||!this.messages){return false}return this._getMessage(key,locale)!==null};Lang.prototype.get=function(key,replacements,locale){if(!this.has(key,locale)){return key}var message=this._getMessage(key,locale);if(message===null){return key}if(replacements){message=this._applyReplacements(message,replacements)}return message};Lang.prototype.trans=function(key,replacements){return this.get(key,replacements)};Lang.prototype.choice=function(key,number,replacements,locale){replacements=typeof replacements!=="undefined"?replacements:{};replacements.count=number;var message=this.get(key,replacements,locale);if(message===null||message===undefined){return message}var messageParts=message.split("|");var explicitRules=[];for(var i=0;i<messageParts.length;i++){messageParts[i]=messageParts[i].trim();if(anyIntervalRegexp.test(messageParts[i])){var messageSpaceSplit=messageParts[i].split(/\s/);explicitRules.push(messageSpaceSplit.shift());messageParts[i]=messageSpaceSplit.join(" ")}}if(messageParts.length===1){return message}for(var j=0;j<explicitRules.length;j++){if(this._testInterval(number,explicitRules[j])){return messageParts[j]}}var pluralForm=this._getPluralForm(number);return messageParts[pluralForm]};Lang.prototype.transChoice=function(key,count,replacements){return this.choice(key,count,replacements)};Lang.prototype._parseKey=function(key,locale){if(typeof key!=="string"||typeof locale!=="string"){return null}var segments=key.split(".");var source=segments[0].replace(/\//g,".");return{source:locale+"."+source,sourceFallback:this.getFallback()+"."+source,entries:segments.slice(1)}};Lang.prototype._getMessage=function(key,locale){locale=locale||this.getLocale();key=this._parseKey(key,locale);if(this.messages[key.source]===undefined&&this.messages[key.sourceFallback]===undefined){return null}var message=this.messages[key.source];var entries=key.entries.slice();var subKey="";while(entries.length&&message!==undefined){var subKey=!subKey?entries.shift():subKey.concat(".",entries.shift());if(message[subKey]!==undefined){message=message[subKey];subKey=""}}if(typeof message!=="string"&&this.messages[key.sourceFallback]){message=this.messages[key.sourceFallback];entries=key.entries.slice();subKey="";while(entries.length&&message!==undefined){var subKey=!subKey?entries.shift():subKey.concat(".",entries.shift());if(message[subKey]){message=message[subKey];subKey=""}}}if(typeof message!=="string"){return null}return message};Lang.prototype._findMessageInTree=function(pathSegments,tree){while(pathSegments.length&&tree!==undefined){var dottedKey=pathSegments.join(".");if(tree[dottedKey]){tree=tree[dottedKey];break}tree=tree[pathSegments.shift()]}return tree};Lang.prototype._applyReplacements=function(message,replacements){for(var replace in replacements){message=message.replace(new RegExp(":"+replace,"gi"),function(match){var value=replacements[replace];var allCaps=match===match.toUpperCase();if(allCaps){return value.toUpperCase()}var firstCap=match===match.replace(/\w/i,function(letter){return letter.toUpperCase()});if(firstCap){return value.charAt(0).toUpperCase()+value.slice(1)}return value})}return message};Lang.prototype._testInterval=function(count,interval){if(typeof interval!=="string"){throw"Invalid interval: should be a string."}interval=interval.trim();var matches=interval.match(intervalRegexp);if(!matches){throw"Invalid interval: "+interval}if(matches[2]){var items=matches[2].split(",");for(var i=0;i<items.length;i++){if(parseInt(items[i],10)===count){return true}}}else{matches=matches.filter(function(match){return!!match});var leftDelimiter=matches[1];var leftNumber=convertNumber(matches[2]);if(leftNumber===Infinity){leftNumber=-Infinity}var rightNumber=convertNumber(matches[3]);var rightDelimiter=matches[4];return(leftDelimiter==="["?count>=leftNumber:count>leftNumber)&&(rightDelimiter==="]"?count<=rightNumber:count<rightNumber)}return false};Lang.prototype._getPluralForm=function(count){switch(this.locale){case"az":case"bo":case"dz":case"id":case"ja":case"jv":case"ka":case"km":case"kn":case"ko":case"ms":case"th":case"tr":case"vi":case"zh":return 0;case"af":case"bn":case"bg":case"ca":case"da":case"de":case"el":case"en":case"eo":case"es":case"et":case"eu":case"fa":case"fi":case"fo":case"fur":case"fy":case"gl":case"gu":case"ha":case"he":case"hu":case"is":case"it":case"ku":case"lb":case"ml":case"mn":case"mr":case"nah":case"nb":case"ne":case"nl":case"nn":case"no":case"om":case"or":case"pa":case"pap":case"ps":case"pt":case"so":case"sq":case"sv":case"sw":case"ta":case"te":case"tk":case"ur":case"zu":return count==1?0:1;case"am":case"bh":case"fil":case"fr":case"gun":case"hi":case"hy":case"ln":case"mg":case"nso":case"xbr":case"ti":case"wa":return count===0||count===1?0:1;case"be":case"bs":case"hr":case"ru":case"sr":case"uk":return count%10==1&&count%100!=11?0:count%10>=2&&count%10<=4&&(count%100<10||count%100>=20)?1:2;case"cs":case"sk":return count==1?0:count>=2&&count<=4?1:2;case"ga":return count==1?0:count==2?1:2;case"lt":return count%10==1&&count%100!=11?0:count%10>=2&&(count%100<10||count%100>=20)?1:2;case"sl":return count%100==1?0:count%100==2?1:count%100==3||count%100==4?2:3;case"mk":return count%10==1?0:1;case"mt":return count==1?0:count===0||count%100>1&&count%100<11?1:count%100>10&&count%100<20?2:3;case"lv":return count===0?0:count%10==1&&count%100!=11?1:2;case"pl":return count==1?0:count%10>=2&&count%10<=4&&(count%100<12||count%100>14)?1:2;case"cy":return count==1?0:count==2?1:count==8||count==11?2:3;case"ro":return count==1?0:count===0||count%100>0&&count%100<20?1:2;case"ar":return count===0?0:count==1?1:count==2?2:count%100>=3&&count%100<=10?3:count%100>=11&&count%100<=99?4:5;default:return 0}};return Lang});(function(){Lang=new Lang();Lang.setMessages({"cs.messages":{"404_page_message":"M\u016f\u017ee to b\u00fdt zp\u016fsobeno t\u00edm, \u017ee jste zadali webovou adresu nespr\u00e1vn\u011b nebo \u017ee\nstr\u00e1nka, kterou jste hledali, mohla b\u00fdt p\u0159esunuta, aktualizov\u00e1na nebo odstran\u011bna. <a href=\":url\">Klikn\u011bte zde<\/a> pro n\u00e1vrat na domovskou str\u00e1nku.","404_page_title":"Error 404 - page not found","accommodation":"ubytov\u00e1n\u00ed","arrival_and_check_in":"p\u0159\u00edjezd a check-in","close":"zav\u0159\u00edt","contact":"kontakt","contact_confirmation_message":"D\u011bkujeme za V\u00e1\u0161 kontakt, budeme V\u00e1s brzy kontaktovat","contact_euro_sportring":"kontakt Euro-Sportring","contact_form_send_btn":"Odelsat dotaz","copyright":"Copyright &copy;","hero_image":"hlavn\u00ed obr\u00e1zek","history":"historie","inquiry_form_success_message":"D\u011bkujeme za V\u00e1\u0161 kontakt, budeme V\u00e1s brzy kontaktovat","language":"jazyk","less":"m\u00e9n\u011b","main_menu":"hlavn\u00ed menu","match_schedule":"Rozlosov\u00e1n\u00ed","match_schedule_message":"Rozlosov\u00e1n\u00ed turnaje k dispozic 2 a\u017e 3 t\u00fddny p\u0159ed za\u010d\u00e1tkem turnaje","matches":"z\u00e1pasy","meals":"strava","media":"media","message_subject":"p\u0159edm\u011bt zpr\u00e1vy","more":"v\u00edce","no_age_category_found":"v\u011bkov\u00e9 kategorie nenalezeny","no_item_found":"polo\u017eka nenalezena","no_itineraries_found":"\u017e\u00e1dn\u00e9 trasy nebyly nalezeny","no_location_found":"lokalita nenalezena","no_photos_found":"fotka nenalezena","no_team_found":"\u017eadn\u00e9 t\u00fdmy nenalezeny","organised_by":"Organizov\u00e1no","program":"program","proudly_sponsored_by":"hrd\u00fd sponsor","public_transport":"ve\u0159ejn\u00e1 doprava","quick_links":"r\u00fdchl\u00fd odkaz","recaptcha_error_message":"pros\u00edm ov\u011b\u0159te captcha","required_field_message":"po\u017eadov\u00e1no","rules":"pravidla","send":"poslat","stay":"pobyt","stay_introduction_content":"Introduction content","teams":"t\u00fdmy","the_teams":"T\u00fdmy","the_venue":"H\u0159i\u0161t\u011b","tips_for_visitors":"tipy pro n\u00e1v\u0161t\u011bvn\u00edky","tourist_information":"turistick\u00e9 informace","tournament":"turnaj","tournament_organiser":"organiz\u00e1tor turnaje","tournament_sponsored_by":"turnaj podporov\u00e1n","tournament_sponsors":"sponzo\u0159i turnaje","travel_accommodation":"Cesta a ubytov\u00e1n\u00ed","venue":"m\u00edsto","visitors":"n\u00e1v\u0161t\u011bvn\u00edci","visitors_arrival_and_check_in":"p\u0159\u00edjezd a check-in","visitors_public_transport":"ve\u0159ejn\u00e1 doprava","visitors_tips_for_visitors":"tipy pro n\u00e1v\u0161t\u011bvn\u00edky","welcome_image":"Uv\u00edtac\u00ed obr\u00e1zek","your telephone number":"telefonn\u00ed \u010d\u00edslo","your_email_address":"Adresa","your_message":"zpr\u00e1va","your_name":"Va\u0161e jm\u00e9no"},"da.messages":{"404_page_message":"Fejl 404 - Fejl p\u00e5 siden","404_page_title":"Fejl 404 - siden findes ikke","accommodation":"Indkvartering","arrival_and_check_in":"Ankomst og check ind","close":"Luk","contact":"Kontakt","contact_confirmation_message":"Vi vender tilbage til dig","contact_euro_sportring":"Kontakt Euro-Sportring","contact_form_send_btn":"Send foresp\u00f8rgsel","copyright":"Copyright &copy;","hero_image":"Hero image","history":"Historie","inquiry_form_success_message":"Vi vender tilbage til dig","language":"Sprog","less":"Mindre","main_menu":"Hovedmenu","match_schedule":"Kampprogram","match_schedule_message":"Kampprogrammet vil v\u00e6re klar 2-3 dage f\u00f8r turneringen starter","matches":"Kampe","meals":"Mad","media":"Medie","message_subject":"Emne","more":"Mere","no_age_category_found":"Ingen alders kategorier fundet","no_item_found":"Ingen side fundet","no_itineraries_found":"Ingen program fundet","no_location_found":"Ingen side fundet","no_photos_found":"Ingen fotos fundet","no_team_found":"Ingen hold fundet","organised_by":"Arrangeret af","program":"Program","proudly_sponsored_by":"Sponsoreret af","public_transport":"Offentlig transport","quick_links":"Hurtige links","recaptcha_error_message":"Please validate reCAPTCHA","required_field_message":"Dette felt er kr\u00e6vet","rules":"Regler","send":"Send","stay":"Ophold","stay_introduction_content":"Introduction content","teams":"Hold","the_teams":"Holdende","the_venue":"Anl\u00e6gget","tips_for_visitors":"Tip til bes\u00f8gende","tourist_information":"Turist informationer","tournament":"Turnering","tournament_organiser":"Turnerings arrang\u00f8r","tournament_sponsored_by":"Turneringen er sponsoreret af","tournament_sponsors":"Turnerings sponsorer","travel_accommodation":"Rejse og indkvartering","venue":"Spillesteder","visitors":"Bes\u00f8gende","visitors_arrival_and_check_in":"Ankomst og check ind","visitors_public_transport":"Offentlig transport","visitors_tips_for_visitors":"Tip til bes\u00f8gende","welcome_image":"Velkommen billede","your telephone number":"Dit telefonnummer","your_email_address":"Din mailadresse","your_message":"Din besked","your_name":"Dit navn"},"de.messages":{"404_page_message":"Bitte\u00a0vergewissern Sie sich, dass\u00a0Sie die korrekte Webadresse\u00a0eingegeben haben.\n\t\t<\/br>Die\u00a0Seite, die Sie suchen,\u00a0kann nicht angezeigt\u00a0werden,\u00a0da\u00a0sie m\u00f6glicherweise\u00a0nicht mehr existiert oder verschoben\u00a0worden\u00a0ist<\/br>Sie k\u00f6nnen durch <a href=\":url\">Klick<\/a> auf das Logo wieder auf die Homepage zur\u00fcckkehren","404_page_title":"Fehlermeldung\u00a0404 - Seite\u00a0kann nicht gefunden werden","accommodation":"Unterkunft","arrival_and_check_in":"Ankunft und Registrierung","close":"Schlie\u00dfen","contact":"Kontakt","contact_confirmation_message":"Vielen Dank f\u00fcr Ihre Kontaktaufnahme. Wir werden uns in K\u00fcrze mit Ihnen in Verbindung setzen.","contact_euro_sportring":"Kontaktieren Sie Euro-Sportring","contact_form_send_btn":"Anfrage versenden","copyright":"Urheberrecht &copy;","hero_image":"Heldenbild","history":"Geschichte","inquiry_form_success_message":"Vielen Dank f\u00fcr Ihre Kontaktaufnahme. Wir werden uns in K\u00fcrze mit Ihnen in Verbindung setzen.","language":"Sprache","less":"Weniger","main_menu":"Hauptmen\u00fc","match_schedule":"Spielplan","match_schedule_message":"Der Spielplan wird 2-3 Tagen vor Turnierbeginn zur Verf\u00fcgung stehen","matches":"Spiele ","meals":"Mahlzeiten","media":"Medien","message_subject":"Betreffzeile","more":"Mehr","no_age_category_found":"Keine Alterskategorien gefunden.","no_item_found":"Keine Elemente gefunden.","no_itineraries_found":"Keine Routen gefunden.","no_location_found":"Keine Orte gefunden","no_photos_found":"Keine Fotos gefunden","no_team_found":"Keine Teams gefunden.","organised_by":"Organisiert von","program":"Programm","proudly_sponsored_by":"Mit grossem Stolz sponsort","public_transport":"\u00f6ffentlicher Nahverkehr","quick_links":"Schnellverkn\u00fcpfung","recaptcha_error_message":"Bitte validiere reCAPTCHA","required_field_message":"Dieses Feld ist erforderlich","rules":"Spielregeln","send":"senden","stay":"Aufenthalt","stay_introduction_content":"Introduction content","teams":"Mannschaften","the_teams":"Die Mannschaften","the_venue":"Der Veranstaltungsort","tips_for_visitors":"n\u00fctzliche\u00a0Tipps\u00a0f\u00fcr\u00a0Besucher","tourist_information":"Touristische Informationen","tournament":"Turnier","tournament_organiser":"Turnierveranstalter","tournament_sponsored_by":"Turnier gesponsert von","tournament_sponsors":"Turniersponsoren","travel_accommodation":"Reisen und Unterkunft","venue":"Veranstaltungsort","visitors":"Besucher","visitors_arrival_and_check_in":"Ankunft und Registrierung","visitors_public_transport":"\u00f6ffentlicher Nahverkehr","visitors_tips_for_visitors":"n\u00fctzliche\u00a0Tipps\u00a0f\u00fcr\u00a0Besucher","welcome_image":"Willkommensbild","your telephone number":"Ihre Telefonnummer","your_email_address":"Ihre E-Mail Adresse","your_message":"Ihre Nachricht","your_name":"Ihren Namen"},"en.auth":{"failed":"These credentials do not match our records.","throttle":"Too many login attempts. Please try again in :seconds seconds."},"en.messages":{"404_page_message":"This might be because you have typed the web address incorrectly, or the page you were looking for may have been moved, updated or deleted. <a href=\":url\">Click here<\/a> to return to the homepage.","404_page_title":"Error 404 - Page not found","accommodation":"Accommodation","arrival_and_check_in":"Arrival and check-in","close":"Close","contact":"Contact","contact_confirmation_message":"Thanks for getting in touch. Someone will get back to you shortly.","contact_euro_sportring":"Contact Euro-Sportring","contact_form_send_btn":"Send Enquiry","copyright":"Copyright &copy;","hero_image":"Hero image","history":"History","inquiry_form_success_message":"Thanks for getting in touch.<br>Someone will get back to you shortly.","language":"Language","less":"Less ...","main_menu":"Main Menu","match_schedule":"Match Schedule","match_schedule_message":"The match schedule will be available 2-3 weeks before the tournament begins.","matches":"Matches","meals":"Meals","media":"Media","message_subject":"Message subject","more":"More ...","no_age_category_found":"No age categories found.","no_item_found":"No items found.","no_itineraries_found":"No itineraries found.","no_location_found":"No locations found.","no_photos_found":"No photos found.","no_team_found":"No teams found.","organised_by":"Organised by","program":"Program","proudly_sponsored_by":"Proudly sponsored by","public_transport":"Public transport","quick_links":"Quick Links","recaptcha_error_message":"Please validate reCAPTCHA","required_field_message":"This field is required","rules":"Rules","send":"Send","stay":"Stay","stay_introduction_content":"Introduction content","teams":"Teams","the_teams":"The teams","the_venue":"The venue","tips_for_visitors":"Tips for visitors","tourist_information":"Tourist information","tournament":"Tournament","tournament_organiser":"Tournament organiser","tournament_sponsored_by":"Tournament sponsored by","tournament_sponsors":"Tournament Sponsors","travel_accommodation":"Travel & Accommodation","venue":"Venue","visitors":"Visitors","visitors_arrival_and_check_in":"Arrival and check-in","visitors_public_transport":"Public transport","visitors_tips_for_visitors":"Tips for visitors","welcome_image":"Welcome image","your_email_address":"Your email address","your_message":"Your message","your_name":"Your name","your_telephone_number":"Your telephone number"},"en.pagination":{"next":"Next &raquo;","previous":"&laquo; Previous"},"en.passwords":{"password":"Passwords must be at least six characters and match the confirmation.","reset":"Your password has been reset!","sent":"We have e-mailed your password reset link!","token":"This password reset token is invalid.","user":"We can't find a user with that e-mail address."},"en.validation":{"accepted":"The :attribute must be accepted.","active_url":"The :attribute is not a valid URL.","after":"The :attribute must be a date after :date.","alpha":"The :attribute may only contain letters.","alpha_dash":"The :attribute may only contain letters, numbers, and dashes.","alpha_num":"The :attribute may only contain letters and numbers.","array":"The :attribute must be an array.","attributes":[],"before":"The :attribute must be a date before :date.","between":{"array":"The :attribute must have between :min and :max items.","file":"The :attribute must be between :min and :max kilobytes.","numeric":"The :attribute must be between :min and :max.","string":"The :attribute must be between :min and :max characters."},"boolean":"The :attribute field must be true or false.","confirmed":"The :attribute confirmation does not match.","custom":{"attribute-name":{"rule-name":"custom-message"}},"date":"The :attribute is not a valid date.","date_format":"The :attribute does not match the format :format.","different":"The :attribute and :other must be different.","digits":"The :attribute must be :digits digits.","digits_between":"The :attribute must be between :min and :max digits.","dimensions":"The :attribute has invalid image dimensions.","distinct":"The :attribute field has a duplicate value.","email":"The :attribute must be a valid email address.","exists":"The selected :attribute is invalid.","file":"The :attribute must be a file.","filled":"The :attribute field is required.","image":"The :attribute must be an image.","in":"The selected :attribute is invalid.","in_array":"The :attribute field does not exist in :other.","integer":"The :attribute must be an integer.","ip":"The :attribute must be a valid IP address.","json":"The :attribute must be a valid JSON string.","max":{"array":"The :attribute may not have more than :max items.","file":"The :attribute may not be greater than :max kilobytes.","numeric":"The :attribute may not be greater than :max.","string":"The :attribute may not be greater than :max characters."},"mimes":"The :attribute must be a file of type: :values.","mimetypes":"The :attribute must be a file of type: :values.","min":{"array":"The :attribute must have at least :min items.","file":"The :attribute must be at least :min kilobytes.","numeric":"The :attribute must be at least :min.","string":"The :attribute must be at least :min characters."},"not_in":"The selected :attribute is invalid.","numeric":"The :attribute must be a number.","present":"The :attribute field must be present.","regex":"The :attribute format is invalid.","required":"The :attribute field is required.","required_if":"The :attribute field is required when :other is :value.","required_unless":"The :attribute field is required unless :other is in :values.","required_with":"The :attribute field is required when :values is present.","required_with_all":"The :attribute field is required when :values is present.","required_without":"The :attribute field is required when :values is not present.","required_without_all":"The :attribute field is required when none of :values are present.","same":"The :attribute and :other must match.","size":{"array":"The :attribute must contain :size items.","file":"The :attribute must be :size kilobytes.","numeric":"The :attribute must be :size.","string":"The :attribute must be :size characters."},"string":"The :attribute must be a string.","timezone":"The :attribute must be a valid zone.","unique":"The :attribute has already been taken.","uploaded":"The :attribute failed to upload.","url":"The :attribute format is invalid."},"fr.messages":{"404_page_message":"Peut-\u00eatre avez-vous fait une faute dans l'adresse web, ou la page que vous recherchez a \u00e9t\u00e9 d\u00e9plac\u00e9e, mise \u00e0 jour ou supprim\u00e9e. <a href=':url'>Cliquez ici<\/a> pour retourner \u00e0 la page d'accueil.","404_page_title":"Erreur 404 - page non trouv\u00e9e","accommodation":"H\u00e9bergement","arrival_and_check_in":"Arriv\u00e9e et enregistrement","close":"Fermer","contact":"Contact","contact_confirmation_message":"Merci pour votre message, nous allons vous r\u00e9pondre sous peu.","contact_euro_sportring":"Contactez Euro-Sportring","contact_form_send_btn":"Envoyer une demande","copyright":"Droits d'auteur","hero_image":"Image h\u00e9ro","history":"Histoire","inquiry_form_success_message":"Merci pour votre message, nous allons vous r\u00e9pondre sous peu.","language":"Langue","less":"Moins ...","main_menu":"Menu principal","match_schedule":"Programme des matchs","match_schedule_message":"Le programme des matchs sera disponible 2-3 jours avant le d\u00e9but du tournoi.","matches":"Matches","meals":"Repas","media":"M\u00e9dias","message_subject":"Sujet du message","more":"Plus ...","no_age_category_found":"Aucune cat\u00e9gorie d'\u00e2ge trouv\u00e9e","no_item_found":"Aucun \u00e9l\u00e9ment trouv\u00e9","no_itineraries_found":"Aucun itin\u00e9raire trouv\u00e9","no_location_found":"Aucun lieu trouv\u00e9.","no_photos_found":"Aucune photo trouv\u00e9e.","no_team_found":"Aucune \u00e9quipe trouv\u00e9e","organised_by":"Organis\u00e9 par","program":"Programme","proudly_sponsored_by":"Fi\u00e8rement sponsoris\u00e9 par ","public_transport":"Transport public","quick_links":"Liens rapides","recaptcha_error_message":"Veuillez valider reCAPTCHA","required_field_message":"Ce champ est obligatoire","rules":"R\u00e8gles","send":"Envoyer","stay":"S\u00e9jour","stay_introduction_content":"Introduction content","teams":"Equipes","the_teams":"Les \u00e9quipes","the_venue":"Le lieu","tips_for_visitors":"Conseils pour les visiteurs","tourist_information":"Informations touristiques","tournament":"Tournoi","tournament_organiser":"Organisateur du tournoi","tournament_sponsored_by":"Tournoi sponsoris\u00e9 par","tournament_sponsors":"Sponsors du tournoi","travel_accommodation":"Voyage & H\u00e9bergement","venue":"Lieu","visitors":"Visiteurs","visitors_arrival_and_check_in":"Arriv\u00e9e et enregistrement","visitors_public_transport":"Transport public","visitors_tips_for_visitors":"Conseils pour les visiteurs","welcome_image":"Image d'accueil","your telephone number":"Votre num\u00e9ro de t\u00e9l\u00e9phone","your_email_address":"Votre adresse email","your_message":"Votre message","your_name":"Votre nom"},"it.messages":{"404_page_message":"Forse hai inserito un indirizzo web sbagliato, o la pagina che hai cercato non esiste pi\u00f9. <a href=\":url\">Clicca qui<\/a> per tornare alla homepage.","404_page_title":"Errore 404 - pagina non trovata","accommodation":"Alloggio","arrival_and_check_in":"Arrivo e registrazione","close":"Chiudi","contact":"Contatti","contact_confirmation_message":"Grazie per averci contattato. Un nostro addetto ti risponder\u00e0 a breve.","contact_euro_sportring":"Contatto Euro-Sportring","contact_form_send_btn":"Invia richiesta","copyright":"Copyright &copy;","hero_image":"Immagine eroe","history":"Storia","inquiry_form_success_message":"Grazie per averci contattato. Un nostro addetto ti risponder\u00e0 a breve.","language":"Lingua","less":"Nascondi \u2026","main_menu":"Menu principale","match_schedule":"Calendario","match_schedule_message":"Il calendario delle partite sar\u00e0 disponibile 2-3 giorni prima dell\u2019inizio del torneo","matches":"Partite","meals":"Pasti","media":"Media","message_subject":"Oggetto","more":"Mostra altri \u2026","no_age_category_found":"Nessuna categoria trovata.","no_item_found":"Nessun oggetto trovato.","no_itineraries_found":"Nessun itinerario trovato.","no_location_found":"Nessuna sede trovata.","no_photos_found":"Nessuna foto trovata.","no_team_found":"Nessuna squadra trovata.","organised_by":"Organizzato da","program":"Programma","proudly_sponsored_by":"Sponsorizzato da","public_transport":"Trasporto pubblico","quick_links":"Links","recaptcha_error_message":"Verifica reCAPTCHA","required_field_message":"Questo campo \u00e8 obbligatorio","rules":"Regolamento","send":"Invia","stay":"Soggiorno","stay_introduction_content":"Introduction content","teams":"Squadre","the_teams":"Squadre","the_venue":"Sede","tips_for_visitors":"Consigli per gli ospiti","tourist_information":"Informazioni turistiche","tournament":"Torneo","tournament_organiser":"Organizzatore del torneo","tournament_sponsored_by":"Torneo sponsorizzato da","tournament_sponsors":"Sponsor del torneo","travel_accommodation":"Viaggio e alloggio","venue":"Sede","visitors":"Ospiti","visitors_arrival_and_check_in":"Arrivo e registrazione","visitors_public_transport":"Trasporto pubblico","visitors_tips_for_visitors":"Consigli per gli ospiti","welcome_image":"Immagine di benvenuto","your telephone number":"Numero di telefono","your_email_address":"Indirizzo email","your_message":"Messaggio","your_name":"Nome"},"nl.messages":{"404_page_message":"<a href=\":url\">klik hier<\/a> om terug te gaan naar de homepage","404_page_title":"Error 404. Pagina niet gevonden","accommodation":"Accommodatie","arrival_and_check_in":"Aankomst","close":"Sluiten","contact":"Contact","contact_confirmation_message":"Bedankt voor uw bericht. We nemen zo spoedig mogelijk contact met u op.","contact_euro_sportring":"Contact","contact_form_send_btn":"Versturen","copyright":"Copyright","hero_image":"Banner","history":"Historie","inquiry_form_success_message":"Bedankt voor uw bericht. We nemen zo spoedig mogelijk contact met u op.","language":"Taal","less":"Minder","main_menu":"Menu","match_schedule":"Wedstrijdschema","match_schedule_message":"Het wedstrijdschema is 2-3 dagen voor het toernooi beschikbaar","matches":"Wedstrijden","meals":"Maaltijden","media":"Media","message_subject":"Onderwerp","more":"Meer","no_age_category_found":"Geen leeftijdscategorie\u00ebn gevonden","no_item_found":"Niets gevonden","no_itineraries_found":"Geen programma","no_location_found":"Geen locatie gevonden","no_photos_found":"Geen foto's beschikbaar","no_team_found":"Geen teams gevonden","organised_by":"Organisator","program":"Programma","proudly_sponsored_by":"Sponsors","public_transport":"Openbaar vervoer","quick_links":"Links","recaptcha_error_message":"Please validate reCAPTCHA","required_field_message":"Verplichte velden","rules":"Toernooi regelement","send":"Zend","stay":"Verblijf","stay_introduction_content":"Introduction content","teams":"Teams","the_teams":"De teams","the_venue":"Locatie","tips_for_visitors":"Tips","tourist_information":"Toeristische informatie","tournament":"Toernooi","tournament_organiser":"Organisator","tournament_sponsored_by":"Sponsors","tournament_sponsors":"Sponsors","travel_accommodation":"Accommodatie","venue":"Locatie","visitors":"Bezoekers","visitors_arrival_and_check_in":"Aankomst","visitors_public_transport":"Openbaar vervoer","visitors_tips_for_visitors":"Tips","welcome_image":"Foto verantwoordelijke toernooi","your telephone number":"Telefoon","your_email_address":"E-mail","your_message":"Bericht","your_name":"Naam"},"pl.messages":{"404_page_message":"Przyczyn\u0105 mo\u017ce by\u0107 niepoprawne wpisanie adresu internetowego lub strony, kt\u00f3rej szukasz, mog\u0142a zosta\u0107 ona przeniesiona, zaktualizowana lub usuni\u0119ta. <a href=\":url\">Kliknij tutaj<\/a>, aby powr\u00f3ci\u0107 na stron\u0119 g\u0142\u00f3wn\u0105.","404_page_title":"B\u0142\u0105d 404 - strona nie zosta\u0142a odnaleziona","accommodation":"Nocleg","arrival_and_check_in":"Przyjazd i zameldowanie","close":"Zamknij","contact":"Kontakt","contact_confirmation_message":"Dzi\u0119kujemy za skontaktowanie si\u0119 z nami. Kto\u015b wkr\u00f3tce skontaktuje si\u0119 z Tob\u0105.","contact_euro_sportring":"Skontaktuj si\u0119 z Euro-Sportring","contact_form_send_btn":"Wy\u015blij zapytanie","copyright":"Copyright &copy;","hero_image":"Obraz bohatera","history":"Historia ","inquiry_form_success_message":"Dzi\u0119kujemy za skontaktowanie si\u0119 z nami. Kto\u015b wkr\u00f3tce skontaktuje si\u0119 z Tob\u0105.","language":"J\u0119zyk","less":"Mniej","main_menu":"Menu g\u0142\u00f3wne","match_schedule":"Harmonogram mecz\u00f3w","match_schedule_message":"Harmonogram mecz\u00f3w b\u0119dzie dost\u0119pny na 2-3 dni przed rozpocz\u0119ciem turnieju","matches":"Mecze","meals":"Posi\u0142ki ","media":"Media","message_subject":"Temat wiadomo\u015bci","more":"Wi\u0119cej","no_age_category_found":"Nie znaleziono kategorii wiekowych.","no_item_found":"Nie znaleziono \u017cadnych przedmiot\u00f3w.","no_itineraries_found":"Nie znaleziono tras","no_location_found":"Nie znaleziono \u017cadnych lokalizacji.","no_photos_found":"Nie znaleziono \u017cadnych zdj\u0119\u0107.","no_team_found":"Nie znaleziono dru\u017cyn.","organised_by":"Organizowany przez","program":"Program","proudly_sponsored_by":"Dumnie sponsorowany przez","public_transport":"Transport publiczny","quick_links":"Szybkie \u0142\u0105cza","recaptcha_error_message":"Prosz\u0119 potwierdzi\u0107 reCAPTCHA","required_field_message":"To pole jest wymagane","rules":"Zasady","send":"Przes\u0142a\u0107","stay":"Pobyt","stay_introduction_content":"Introduction content","teams":"Dru\u017cyny","the_teams":"Dru\u017cyny","the_venue":"Miejsce","tips_for_visitors":"Wskaz\u00f3wki dla odwiedzaj\u0105cych","tourist_information":"Informacja turystyczna","tournament":"Turniej","tournament_organiser":"Organizator turnieju","tournament_sponsored_by":"Turniej sponsorowany przez","tournament_sponsors":"Sponsorzy turnieju","travel_accommodation":"Podr\u00f3\u017c i zakwaterowanie","venue":"Miejsce spotkania","visitors":"Go\u015bcie","visitors_arrival_and_check_in":"Przyjazd i zameldowanie","visitors_public_transport":"Transport publiczny","visitors_tips_for_visitors":"Wskaz\u00f3wki dla odwiedzaj\u0105cych","welcome_image":"Obraz powitalny","your telephone number":"Tw\u00f3j numer telefonu","your_email_address":"Tw\u00f3j adres email","your_message":"Twoja wiadomo\u015b\u0107","your_name":"Twoje imi\u0119"}});})();