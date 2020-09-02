//
//  LandingVC.swift
//  ESR
//
//  Created by Pratik Patel on 13/08/18.
//

import UIKit
import FacebookCore
import FacebookLogin
import AuthenticationServices
import SwiftKeychainWrapper

class LandingVC: SuperViewController {

    @IBOutlet var lblAppVersion: UILabel!
    
    @IBOutlet var btnSignIn: UIButton!
    @IBOutlet var btnCreateAccount: UIButton!
    @IBOutlet var btnLoginWithFacebook: UIButton!
    
    var authToken = NULL_STRING
    var isAutoLogin = false
    
    @IBOutlet var appleSignInView: UIView!
    
    var paramEmail = NULL_STRING
    var paramFirstName = NULL_STRING
    var paramLastName = NULL_STRING
    var paramUserIdentifier = NULL_STRING
    
    enum SocialLoginProvider: String {
        case facebook = "facebook"
        case apple = "apple"
    }
    
    private var socialLoginProvider = SocialLoginProvider.facebook.rawValue

    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    func initialize() {
        self.navigationController?.isNavigationBarHidden = true
        lblAppVersion.textColor = UIColor.AppColor()
        
        appleSignInView.addGestureRecognizer(UITapGestureRecognizer(target: self, action: #selector(handleAuthorizationAppleIDButtonPress)))
        ApplicationData.setBorder(view: appleSignInView, Color: .clear, CornerRadius: appleSignInView.frame.size.height / 2, Thickness: 1.0)
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnSignIn.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnCreateAccount.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnSignIn.setTitleColor(.white, for: .normal)
            btnCreateAccount.setTitleColor(.white, for: .normal)
        }
        
        // Localization
        btnSignIn.setTitle(String.localize(key: "Sign in"), for: .normal)
        btnCreateAccount.setTitle(String.localize(key: "Create account"), for: .normal)
        
        // Sets app version
        if let version = Bundle.main.infoDictionary?["CFBundleShortVersionString"] as? String {
            lblAppVersion.text = String.init(format: String.localize(key: "string_app_version"), arguments: [version])
        }
        
        if USERDEFAULTS.string(forKey: kUserDefaults.token) != nil {
            isAutoLogin = true
            
            if USERDEFAULTS.bool(forKey: kUserDefaults.isFacebookLogin) {
                getUserDetailsAPI()
            } else {
                autoLoginAndUpdateToken()
            }
        } else {
            // Checks if new app version is available or not
            sendAppversionRequest()
        }
    }
    
    @objc func handleAuthorizationAppleIDButtonPress() {
        let appleIDProvider = ASAuthorizationAppleIDProvider()
        let request = appleIDProvider.createRequest()
        request.requestedScopes = [.fullName, .email]
        
        let authorizationController = ASAuthorizationController(authorizationRequests: [request])
        authorizationController.delegate = self
        authorizationController.presentationContextProvider = self
        authorizationController.performRequests()
    }
    
    @IBAction func btnLoginWithFacebookPressed(_ sender: UIButton) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        LoginManager().logIn(permissions: [.publicProfile, .email], viewController: self) { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
            switch result {
            case .cancelled:
                print("Login Cancelled - User cancelled login.")
                break
            case .failed(let error):
                print("Login Fail - Login failed with error \(error)")
            case .success(_, _, let accessToken):
                self.authToken = accessToken.tokenString
                self.socialLoginProvider = SocialLoginProvider.facebook.rawValue
                self.socialLoginAPI()
            }
        }
    }
    
    @IBAction func createAccountBtnPressed(_ sender: UIButton) {
        self.navigationController?.pushViewController(Storyboards.Main.instantiateCreateAccountVC(), animated: true)
    }
    
    @IBAction func signInBtnPressed(_ sender: UIButton) {
        self.navigationController?.pushViewController(Storyboards.Main.instantiateLoginVC(), animated: true)
    }
}

extension LandingVC: ASAuthorizationControllerPresentationContextProviding {
    
    func presentationAnchor(for controller: ASAuthorizationController) -> ASPresentationAnchor {
        return self.view.window!
    }
}

extension LandingVC: ASAuthorizationControllerDelegate {
    func authorizationController(controller: ASAuthorizationController, didCompleteWithAuthorization authorization: ASAuthorization) {
        switch authorization.credential {
            case let appleIDCredential as ASAuthorizationAppleIDCredential:
                
                if let email = appleIDCredential.email {
                    paramEmail = email
                } else {
                    if let email = KeychainWrapper.standard.string(forKey: "email") {
                        paramEmail = email
                        print("Keychain retrived email")
                    }
                }

                if let lastName = appleIDCredential.fullName?.familyName {
                    paramLastName = lastName
                } else {
                    if let lastName = KeychainWrapper.standard.string(forKey: "lastName") {
                        paramLastName = lastName
                        print("Keychain retrived lastName")
                    }
                }

                if let givenName = appleIDCredential.fullName?.givenName {
                    paramFirstName = givenName
                } else {
                    if let givenName = KeychainWrapper.standard.string(forKey: "firstName") {
                        paramFirstName = givenName
                        print("Keychain retrived firstName")
                    }
                }

                var saveSuccessful: Bool = KeychainWrapper.standard.set(paramEmail, forKey: "email")
                print("Keychain saved email \(saveSuccessful)")
                saveSuccessful = KeychainWrapper.standard.set(paramLastName, forKey: "lastName")
                print("Keychain saved lastName \(saveSuccessful)")
                saveSuccessful = KeychainWrapper.standard.set(paramFirstName, forKey: "firstName")
                print("Keychain saved lastName \(saveSuccessful)")

                paramUserIdentifier = appleIDCredential.user
                socialLoginProvider = SocialLoginProvider.apple.rawValue
                socialLoginAPI()
            default:
                break
        }
    }
    
    func authorizationController(controller: ASAuthorizationController, didCompleteWithError error: Error) {}
}

extension LandingVC: CustomAlertTwoBtnVCDelegate {
    func customAlertTwoBtnVCNoBtnPressed(requestCode: Int) {}
    
    func customAlertTwoBtnVCYesBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.appUpgrade.rawValue {
            if let url = URL(string: APPSTORE_APP_URL),
                UIApplication.shared.canOpenURL(url){
                if #available(iOS 10.0, *) {
                    UIApplication.shared.open(url, options: [:], completionHandler: nil)
                } else {
                    UIApplication.shared.openURL(url)
                }
            }
        }
    }
}

extension LandingVC {
    
    func autoLoginAndUpdateToken() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        if !USERDEFAULTS.bool(forKey: kUserDefaults.isLogin) {
            return
        }
        
        var parameters: [String: Any] = [:]
        
        if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String,  let password = USERDEFAULTS.value(forKey: kUserDefaults.password) as? String {
            parameters["email"] = email
            parameters["password"] = password
            
            self.view.showProgressHUD()
            
            ApiManager().login(parameters, success: { result in
                DispatchQueue.main.async {
                    self.view.hideProgressHUD()
                    if let token = result.value(forKey: "token") as? String {
                        USERDEFAULTS.set(token, forKey: kUserDefaults.token)
                    }

                    if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                        keyWindow.rootViewController = Storyboards.Main.instantiateMainVC()
                    }
                }
            }, failure: { result in
                DispatchQueue.main.async {
                    self.view.hideProgressHUD()
                    if result.allKeys.count == 0 {
                        return
                    }
                }
            })
        }
    }

    func sendAppversionRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        let parameters: [String: Any] = [:]
        ApiManager().getAppVersion(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()

                if let enable_testfairy_ios = result.value(forKey: "enable_testfairy_ios") as? Int {
                    if enable_testfairy_ios == 1 {

                        TestFairy.begin("SDK-7273syUD")

                        if let enable_testfairy_feedback_ios = result.value(forKey: "enable_testfairy_feedback_ios") as? Int {
                            if enable_testfairy_feedback_ios == 1 {
                                TestFairy.enableFeedbackForm("shake")
                            } else {
                                TestFairy.disableFeedbackForm()
                            }
                        }

                        if let enable_testfairy_video_capture_ios = result.value(forKey: "enable_testfairy_video_capture_ios") as? Int {
                            if enable_testfairy_video_capture_ios == 1 {
                                TestFairy.enableVideo("always", quality: "high", framesPerSecond: 1.0)
                            } else {
                                TestFairy.disableVideo()
                            }
                        }

                    } else {
                        TestFairy.begin(nil)
                    }
                }

                if let serverVersion = result.value(forKey: "ios_app_version") as? String {
                    let appVersion = Bundle.main.object(forInfoDictionaryKey: "CFBundleShortVersionString") as! String
                    
                    if Utils.compareVersion(serverVersion, appVersion) != 0 {
                        self.showCustomAlertTwoBtnVC(title: String.localize(key: "alert_title_app_update"), message: String.localize(key: "alert_msg_app_update"), buttonYesTitle: String.localize(key: "btn_update"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.appUpgrade.rawValue, delegate: self)
                        
                        ApplicationData.isAppUpdateDispalyed = true
                    }
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 { return }
                
                if let error = result.value(forKey: "error") as? String {
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
    }
    
    // MARK: Facebook
    
    func socialLoginAPI() {
        if APPDELEGATE.reachability.connection == .none {
            self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: String.localize(key: "string_no_internet"))
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        parameters["provider"] = socialLoginProvider
        
        if socialLoginProvider == SocialLoginProvider.apple.rawValue {
            parameters["first_name"] = self.paramFirstName
            parameters["last_name"] = self.paramLastName
            parameters["email"] = self.paramEmail
            parameters["user_identifier"] = self.paramUserIdentifier
        } else {
            parameters["token"] = self.authToken
        }
        
        ApiManager().socialMediaLogin(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                if let token = result.value(forKey: "token") as? String {
                    USERDEFAULTS.set(token, forKey: kUserDefaults.token)
                    USERDEFAULTS.set(true, forKey: kUserDefaults.isFacebookLogin)
                    
                    self.getUserDetailsAPI()
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "message") as? String {
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
    }
    
    func getUserDetailsAPI() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        let parameters: [String: Any] = [:]
        
        self.view.showProgressHUD()
        ApiManager().getUserDetails(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let authenticated = result.value(forKey: "authenticated") as? Bool {
                    if authenticated {
                        ParseManager.parseLogin(result)
                        
                        let mainVC = Storyboards.Main.instantiateMainVC()
                        
                        if let userData = ApplicationData.sharedInstance().getUserData() {
                            if userData.email == NULL_STRING {
                                ApplicationData.facebookDetailsPending = true
                            } else {
                                if userData.countryId == NULL_ID {
                                    mainVC.skipCountryCheck = false
                                }
                            }
                            
                            if !self.isAutoLogin {
                                ApplicationData.temLoginFlag = true
                            }
                        }
                        
                        if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                            keyWindow.rootViewController = mainVC
                        }
                    }
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "error") as? String {
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error, buttonTitle: String.localize(key: "btn_ok"))
                }
            }
        })
    }
}

