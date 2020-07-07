//
//  MainTabViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//
import UIKit
    
protocol MainTabViewControllerDelegate {
    func mainTabViewControllerSelectTab(_ tabIndex: Int)
}

class MainTabViewController: SuperViewController {
    
    @IBOutlet var tabButtonList: [UIButton]!
    @IBOutlet var tabLabelList: [UILabel]!
    @IBOutlet var contentView: UIView!
    @IBOutlet var tabBarContainerView: UIView!
    
    var selectedIndex = 0
    var previousIndex = 0
    
    var viewControllers: [UIViewController]!
    
    var delegate: MainTabViewControllerDelegate?
    
    var tournamentDetailsView: TournamentDetailsView!
    
    @IBOutlet var heightConstraintTabbarContainerView: NSLayoutConstraint!
    
    var skipCountryCheck = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        updateFCMTokenAPI()
        self.view.backgroundColor = .AppColor()
        
        /*if let userData = ApplicationData.sharedInstance().getUserData() {
            if userData.enableLogs {
                TestFairy.begin("SDK-7273syUD")
            } else {
                TestFairy.begin(NULL_STRING)
            }
        }*/
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .accessCodeAPI, object: nil)
    }
    
    override func viewDidLayoutSubviews() {
        tournamentDetailsView.frame = CGRect(x: 0, y: 0, width: DEVICE_WIDTH, height: DEVICE_HEIGHT)
    }
    
    override var prefersStatusBarHidden: Bool {
        get {
            return false
        }
    }
    
    func initialize(){
        
        _ = cellOwner.loadMyNibFile(nibName: "TournamentDetailsView")
        tournamentDetailsView = cellOwner.view as! TournamentDetailsView
        tournamentDetailsView!.hide()
        self.view.addSubview(tournamentDetailsView)
        
        updateAppVersionAPI()
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            NotificationCenter.default.addObserver(self, selector: #selector(goToTabFollow(_:)), name: .goToTabFollow, object: nil)
            NotificationCenter.default.addObserver(self, selector: #selector(callAccessCodeAPI(_:)), name: .accessCodeAPI, object: nil)
        }
        
        APPDELEGATE.mainTabVC = self
        
        for i in 0..<tabButtonList.count{
            let button = tabButtonList[i]
            button.tag = i
            button.addTarget(self, action: #selector(onTabSelected(btn:)), for: .touchUpInside)
            
            refeshTabTitle()
            
            if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
                
                var image = UIImage(named: "tab_favorites")
                
                if i == TabIndex.tabTournament.rawValue {
                    image = UIImage(named: "tab_tournament")
                } else if i == TabIndex.tabTeams.rawValue {
                    image = UIImage(named: "tab_teams")
                } else if i == TabIndex.tabAgeCategories.rawValue {
                    image = UIImage(named: "tab_age_categories")
                } else if i == TabIndex.tabsettings.rawValue {
                    image = UIImage(named: "tab_settings")
                }
                
                if let modifiedImage = image?.withRenderingMode(.alwaysTemplate) {
                    button.setImageColor(color: UIColor.AppColor(), image: modifiedImage, state: .selected)
                }
            }
        }
        
        setupTabs()
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            mainTabViewControllerSelectTab(TabIndex.tabFav.rawValue)
        } else {
            mainTabViewControllerSelectTab(TabIndex.tabTournament.rawValue)
        }
        
        // If it is displayed before then it won't display again
        if !ApplicationData.isAppUpdateDispalyed {
            // Checks if new app version is available or not
            sendAppversionRequest()
        }
        
        /*if ApplicationData.facebookDetailsPending {
            DispatchQueue.main.asyncAfter(deadline: .now() + 1) {
                self.onTabSelected(btn: self.tabButtonList[TabIndex.tabsettings.rawValue])
            }
            return
        }*/
        
        // Checks if the application is redirected from deep link
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            if ApplicationData.accessCodeFromURL != NULL_STRING {
                skipCountryCheck = true
            }
        }
        
        if skipCountryCheck {
            accessCodeAPI()
        }
        
        /*if !skipCountryCheck {
            if let userDetails = ApplicationData.sharedInstance().getUserData() {
                if userDetails.countryId == NULL_ID {
                    
                    onTabSelected(btn: tabButtonList[TabIndex.tabsettings.rawValue])
                    
                    DispatchQueue.main.asyncAfter(deadline: .now() + 1) {
                        NotificationCenter.default.post(name: .selectCountry, object: nil)
                    }
                }
            }
        } else {
            accessCodeAPI()
        }*/
    }

    @objc func callAccessCodeAPI(_ notification: NSNotification) {
        TestFairy.log(String(describing: self) + " callAccessCodeAPI")
        accessCodeAPI()
    }
    
    @objc func goToTabFollow(_ notification: NSNotification) {
        TestFairy.log(String(describing: self) + " goToTabFollow")
        onTabSelected(btn: tabButtonList[TabIndex.tabFav.rawValue])
    }
    
    func accessCodeAPI() {
        if APPDELEGATE.reachability.connection == .none {
            self.view.hideProgressHUD()
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["accessCode"] = ApplicationData.accessCodeFromURL
        
        self.view.showProgressHUD()
        ApiManager().accessCode(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let dicTournament = result.value(forKey: "data") as? NSDictionary {
                    ApplicationData.accessCodeFromURL = NULL_STRING
                    
                    let tournament = ParseManager.parseTournament(dicTournament)
                    
                    if let userData = ApplicationData.sharedInstance().getUserData() {
                        userData.tournamentId = tournament.id
                        ApplicationData.sharedInstance().saveUserData(userData)
                    }
                    
                    ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                    self.onTabSelected(btn: self.tabButtonList[TabIndex.tabTournament.rawValue])
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                self.onTabSelected(btn: self.tabButtonList[TabIndex.tabFav.rawValue])
                
                if let message = result.value(forKey: "message") as? String {
                    self.showCustomAlertVC(title: result.value(forKey: "title") as? String ?? String.localize(key: "alert_title_error"), message: message)
                }
            }
        })
    }
    
    func updateAppVersionAPI() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["device"] = "iOS"
        parameters["app_version"] = Bundle.main.object(forInfoDictionaryKey: "CFBundleShortVersionString") as! String
        parameters["os_version"] = "\(UIDevice.current.systemVersion)"
        
        if let user = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = user.id
        }
        
        ApiManager().updateAppVersion(parameters, success: { result in
        }, failure: { result in })
    }
    
    func refeshTabTitle() {
        
        let tabFavString = (ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue) ? String.localize(key: "title_tab_follow") : String.localize(key: "title_tab_fav")
        
        var tabTitleList = [tabFavString, String.localize(key: "title_tab_tournament"), String.localize(key: "title_tab_teams"), String.localize(key: "title_tab_agecategories"), String.localize(key: "title_tab_settings")]
        
        for i in 0..<tabLabelList.count {
            tabLabelList[i].text = tabTitleList[i]
        }
    }
    
    func hideTabbar(flag: Bool = true) {
        tabBarContainerView.isHidden = flag ? true : false
        heightConstraintTabbarContainerView.constant = flag ? 0 : 60
        self.updateViewConstraints()
    }
    
    func sendAppversionRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        let parameters: [String: Any] = [:]
        ApiManager().getAppVersion(parameters, success: { result in
            DispatchQueue.main.async {
                TestFairy.log("sendAppversionRequest success")
                self.view.hideProgressHUD()
                if let serverVersion = result.value(forKey: "ios_app_version") as? String {
                    let appVersion = Bundle.main.object(forInfoDictionaryKey: "CFBundleShortVersionString") as! String
                    
                    // 1 - left version is greater than right version
                    if Utils.compareVersion(serverVersion, appVersion) == 1 {
                        self.showCustomAlertTwoBtnVC(title: String.localize(key: "alert_title_app_update"), message: String.localize(key: "alert_msg_app_update"), buttonYesTitle: String.localize(key: "btn_update"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.appUpgrade.rawValue, delegate: self)
                    }
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                TestFairy.log("sendAppversionRequest failure")
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "error") as? String {
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
    }
    
    func setupTabs() {
        
        var vc: UIViewController? = nil
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            vc = initFollowVC()
        } else {
            vc = initFavouritesVC()
        }
        
        viewControllers = [vc!, initTournamentVC(), initTeamsVC(), initAgeCategoriesVC(), initSettingsVC()]
        addViewControllerToContentView(false)
    }
    
    func initFavouritesVC() -> UINavigationController {
        let tabFavoritesVC = Storyboards.Favourites.instantiateFavouritesVC()
        return UINavigationController(rootViewController: tabFavoritesVC)
    }
    
    func initFollowVC() -> UINavigationController {
        let tabFollowVC = Storyboards.Favourites.instantiateFollowVC()
        tabFollowVC.delegate = self
        return UINavigationController(rootViewController: tabFollowVC)
    }
    
    func initTournamentVC() -> UINavigationController {
        let tabTournamentVC = Storyboards.Tournament.instantiateTournamentVC()
        tabTournamentVC.delegate = self
        return UINavigationController(rootViewController: tabTournamentVC)
    }
    
    func initTeamsVC() -> UINavigationController {
        let tabTeamsVC = Storyboards.Teams.instantiateTabTeamsVC()
        return UINavigationController(rootViewController: tabTeamsVC)
    }
    
    func initAgeCategoriesVC() -> UINavigationController {
        let tabAgeCategoriesVC = Storyboards.AgeCategories.instantiateAgeCategoriesVC()
        return UINavigationController(rootViewController: tabAgeCategoriesVC)
    }
    
    func initSettingsVC() -> UINavigationController {
        let tabSettingsVC = Storyboards.Settings.instantiateSettingsVC()
        return UINavigationController(rootViewController: tabSettingsVC)
    }
    
    func addViewControllerToContentView(_ flag: Bool) {
        
        if flag {
            if selectedIndex == 0 {
                if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
                    viewControllers[selectedIndex] = initFollowVC()
                } else {
                    viewControllers[selectedIndex] = initFavouritesVC()
                }
            } else if selectedIndex == 1 {
                viewControllers[selectedIndex] = initTournamentVC()
            } else if selectedIndex == 2 {
                viewControllers[selectedIndex] = initTeamsVC()
            } else if selectedIndex == 3 {
                viewControllers[selectedIndex] = initAgeCategoriesVC()
            } else if selectedIndex == 4 {
                viewControllers[selectedIndex] = initSettingsVC()
            }
        }
        
        tabButtonList[selectedIndex].isSelected = true
        
        let vc = viewControllers[selectedIndex]
        
        addChildViewController(vc)
        vc.view.frame = contentView.bounds
        contentView.addSubview(vc.view)
        vc.didMove(toParentViewController: self)
    }
    
    @objc func onTabSelected(btn: UIButton) {
        TestFairy.log(String(describing: self) + " onTabSelected")
        
        if selectedIndex != btn.tag {
            selectedIndex = btn.tag
            
            if ApplicationData.facebookDetailsPending && selectedIndex != 4 {
                self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: String.localize(key: "alert_msg_facebook_profile_details"))
                return
            }
            
            if selectedIndex == 2 || selectedIndex == 3 {
                if ApplicationData.sharedInstance().isTournamentInPreview() {
                    self.showCustomAlertVC(title: String.localize(key: "alert_title_preview"), message: String.localize(key: "alert_preview_tournament"))
                    return
                }
            }
            
            if selectedIndex != previousIndex {
                tabButtonList[previousIndex].isSelected = false
                previousIndex = selectedIndex
            }
            
            // Remove previous view controller
            let previousVC = viewControllers[previousIndex]
            previousVC.willMove(toParentViewController: nil)
            previousVC.view.removeFromSuperview()
            previousVC.removeFromParentViewController()
            
            addViewControllerToContentView(true)
        }
    }
}

extension MainTabViewController: MainTabViewControllerDelegate {
    func mainTabViewControllerSelectTab(_ tabIndex: Int) {
        onTabSelected(btn: tabButtonList[tabIndex])
    }
}

extension MainTabViewController: CustomAlertTwoBtnVCDelegate {
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

extension MainTabViewController {
    func updateFCMTokenAPI() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        if let fcmToken = USERDEFAULTS.string(forKey: kUserDefaults.fcmToken) {
            print("FCM token\n")
            print("\(fcmToken)")
            print("\n")
            var parameters: [String: Any] = [:]
            
            if let email = USERDEFAULTS.value(forKey: kUserDefaults.email) as? String {
                parameters["email"] = email
                parameters["fcm_id"] = fcmToken
                
                ApiManager().updateFCMTokem(parameters, success: { result in
                    print("FCM token has updated")
                }, failure: { result in })
            }
        }
    }
}

