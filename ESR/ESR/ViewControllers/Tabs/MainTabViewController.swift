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
    
    @IBOutlet var heightConstraintTabbarContainerView: NSLayoutConstraint!
    
    var isFromLanding = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize(){
        for i in 0..<tabButtonList.count{
            let button = tabButtonList[i]
            button.tag = i
            button.addTarget(self, action: #selector(onTabSelected(btn:)), for: .touchUpInside)
        }
        
        setupTabs()
        initInfoAlertViewTwoButton(self.view, self)
        
        mainTabViewControllerSelectTab(TabIndex.tabTournament.rawValue)
        
        // If it is displayed before then it won't display again
        if !ApplicationData.isAppUpdateDispalyed {
            // Checks if new app version is available or not
            sendAppversionRequest()
        }
    }
    
    func hideTabbar(flag: Bool = true) {
        heightConstraintTabbarContainerView.constant = flag ? 0 : 60
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
                    let appVersion = Bundle.main.object(forInfoDictionaryKey: "CFBundleVersion") as! String
                    
                    // 1 - left version is greater than right version
                    if Utils.compareVersion(serverVersion, appVersion) == 1 {
                        self.showInfoAlertViewTwoButton(title: String.localize(key: "alert_title_app_update"), message: String.localize(key: "alert_msg_app_update"), buttonYesTitle: String.localize(key: "btn_update"), buttonNoTitle: String.localize(key: "btn_cancel"), requestCode: AlertRequestCode.appUpgrade.rawValue)
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
                    self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: error)
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
    
    func initTournamentVC() -> UINavigationController {
        let tabTournamentVC = Storyboards.Tournament.instantiateTournamentVC()
        tabTournamentVC.delegate = self
        return UINavigationController(rootViewController: tabTournamentVC)
    }
    
    func initTeamsVC() -> UINavigationController {
        let tabTeamsVC = Storyboards.Teams.instantiateTeamsVC()
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
        tabButtonList[selectedIndex].isSelected = true
        
        if flag {
            if selectedIndex == 0 {
                viewControllers[selectedIndex] = initFavouritesVC()
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
        
        let vc = viewControllers[selectedIndex]
        
        addChildViewController(vc)
        vc.view.frame = contentView.bounds
        contentView.addSubview(vc.view)
        vc.didMove(toParentViewController: self)
    }
    
    @objc func onTabSelected(btn: UIButton) {
        if selectedIndex != btn.tag {
            selectedIndex = btn.tag
            
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

extension MainTabViewController: CustomAlertViewTwoButtonDelegate {
    func customAlertViewTwoButtonNoBtnPressed(requestCode: Int) {}
    
    func customAlertViewTwoButtonYesBtnPressed(requestCode: Int) {
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
