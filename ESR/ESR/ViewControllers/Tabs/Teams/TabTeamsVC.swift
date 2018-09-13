//
//  TabTeamsViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TabTeamsVC: SuperViewController {

    @IBOutlet var tabClubsView: UIView!
    @IBOutlet var tabCategoryView: UIView!
    @IBOutlet var tabGroupsView: UIView!
    
    @IBOutlet var tabClubsLineView: UIView!
    @IBOutlet var tabCategoryLineView: UIView!
    @IBOutlet var tabGroupLineView: UIView!
    
    @IBOutlet var lblTabClubs: UILabel!
    @IBOutlet var lblTabCategory: UILabel!
    @IBOutlet var lblTabGroup: UILabel!
    
    var selectedTab = 0
    
    enum ClubTabs: Int {
        case tabClubs = 0
        case tabCategory = 1
        case tabGroups = 2
    }
    
    private lazy var clubClubsVC: ClubClubsVC = {
        let viewController = Storyboards.Teams.instantiateClubClubsVC()
        self.addChildViewController(viewController)
        return viewController
    }()
    
    private lazy var clubCategoryVC: ClubCategoryVC = {
        let viewController = Storyboards.Teams.instantiateClubCategoryVC()
        self.addChildViewController(viewController)
        return viewController
    }()
    
    private lazy var clubGroupVC: ClubGroupVC = {
        let viewController = Storyboards.Teams.instantiateClubGroupVC()
        self.addChildViewController(viewController)
        return viewController
    }()
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        self.navigationController?.isNavigationBarHidden = true
        initialize()
    }
    
    func initialize(){
        titleNavigationBar.lblTitle.text = String.localize(key: "title_teams")
        titleNavigationBar.setBackgroundColor()
        titleNavigationBar.hideBackButton()
        
        var gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onTabClubsViewPressed))
        self.tabClubsView.addGestureRecognizer(gesture)
        
        gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onTabCategoryViewPressed))
        self.tabCategoryView.addGestureRecognizer(gesture)
        
        gesture = UITapGestureRecognizer(target: self, action:  #selector(self.onTabGroupsViewPressed))
        self.tabGroupsView.addGestureRecognizer(gesture)
        
        let viewController = Storyboards.Teams.instantiateClubClubsVC()
        addTabViewcontroller(viewController)
    }
    
    func addTabViewcontroller(_ viewController: UIViewController) {
        // Add Child View Controller
        self.addChildViewController(viewController)
         // Add Child View as Subview
        self.view.addSubview(viewController.view)
        
        let tabBarHeight = APPDELEGATE.tabBarController?.tabBar.frame.size.height
        
        // Status bar + Navigation bar + tab bar
        let topViewHeight: CGFloat = 20 + 44 + 45
        
        // Configure Child View
        viewController.view.frame = CGRect(x: self.view.frame.origin.x, y: self.view.frame.origin.y + topViewHeight, width: self.view.frame.size.width, height: self.view.frame.size.height - tabBarHeight! - topViewHeight)
        viewController.view.autoresizingMask = [.flexibleWidth, .flexibleHeight]
        
        // Notify Child View Controller
        viewController.didMove(toParentViewController: self)
    }
    
    func removeTabViewcontroller(_ viewController: UIViewController) {
        // Notify Child View Controller
        viewController.willMove(toParentViewController: nil)
        // Remove Child View From Superview
        viewController.view.removeFromSuperview()
        // Notify Child View Controller
        viewController.removeFromParentViewController()
    }
    
    func updateView() {
        tabClubsLineView.backgroundColor = .clear
        tabCategoryLineView.backgroundColor = .clear
        tabGroupLineView.backgroundColor = .clear
        
        lblTabClubs.textColor = .teamTabLblDefault
        lblTabGroup.textColor = .teamTabLblDefault
        lblTabCategory.textColor = .teamTabLblDefault
        
        if selectedTab == ClubTabs.tabClubs.rawValue {
            removeTabViewcontroller(clubGroupVC)
            removeTabViewcontroller(clubCategoryVC)
            addTabViewcontroller(clubClubsVC)
            
            tabClubsLineView.backgroundColor = .teamTabOrange
            lblTabClubs.textColor = .white
        } else if selectedTab == ClubTabs.tabCategory.rawValue {
            removeTabViewcontroller(clubClubsVC)
            removeTabViewcontroller(clubGroupVC)
            addTabViewcontroller(clubCategoryVC)
            
            tabCategoryLineView.backgroundColor = .teamTabOrange
            lblTabCategory.textColor = .white
        } else if selectedTab == ClubTabs.tabGroups.rawValue {
            removeTabViewcontroller(clubClubsVC)
            removeTabViewcontroller(clubCategoryVC)
            addTabViewcontroller(clubGroupVC)
            
            tabGroupLineView.backgroundColor = .teamTabOrange
            lblTabGroup.textColor = .white
        }
    }
    
    @objc func onTabClubsViewPressed(sender : UITapGestureRecognizer) {
        selectedTab = ClubTabs.tabClubs.rawValue
        updateView()
    }
    
    @objc func onTabCategoryViewPressed(sender : UITapGestureRecognizer) {
        selectedTab = ClubTabs.tabCategory.rawValue
        updateView()
    }
    
    @objc func onTabGroupsViewPressed(sender : UITapGestureRecognizer) {
        selectedTab = ClubTabs.tabGroups.rawValue
        updateView()
    }
}
