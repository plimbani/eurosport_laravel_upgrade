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
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
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
        
        if let userDetails = ApplicationData.sharedInstance().getUserData() {
            if userDetails.countryId == NULL_ID {
                
                onTabSelected(btn: tabButtonList[TabIndex.tabsettings.rawValue])
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 1) {
                    NotificationCenter.default.post(name: .selectCountry, object: nil)
                }
            }
        }
        
        _ = cellOwner.loadMyNibFile(nibName: "TournamentDetailsView")
        tournamentDetailsView = cellOwner.view as! TournamentDetailsView
        tournamentDetailsView!.hide()
        self.view.addSubview(tournamentDetailsView)
        
        updateAppVersionAPI()
    }
    
    func updateAppVersionAPI() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        // self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        parameters["device"] = "iOS"
        parameters["app_version"] = Bundle.main.object(forInfoDictionaryKey: "CFBundleShortVersionString") as! String
        parameters["os_version"] = "\(UIDevice.current.systemVersion)"
        
        if let user = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = user.id
        }
        
        ApiManager().updateAppVersion(parameters, success: { result in
            DispatchQueue.main.async {
                // self.view.hideProgressHUD()
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
                }
                
                if let error = result.value(forKey: "error") as? String {
                    // self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: error)
                }
            }
        })
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
        viewControllers = [initFavouritesVC(), initTournamentVC(), initTeamsVC(), initAgeCategoriesVC(), initSettingsVC()]
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
        
        if selectedIndex != btn.tag {
            selectedIndex = btn.tag
            
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

