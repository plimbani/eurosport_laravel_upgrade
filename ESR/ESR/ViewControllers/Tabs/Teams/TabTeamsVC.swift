//
//  TabTeamsViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TabTeamsVC: SuperViewController {

    @IBOutlet var contentView: UIView!
    
    @IBOutlet var tabViewList: [UIView]!
    @IBOutlet var tabLineViewList: [UIView]!
    @IBOutlet var tabLabelList: [UILabel]!
    
    var selectedIndex = 0
    var previousIndex = 0
    var viewControllers: [UIViewController]!
    
    var rotateToPortrait = false
    var selectedIndexForRotation = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        self.navigationController?.isNavigationBarHidden = true
        initialize()
    }
    
    override func viewDidLayoutSubviews() {
        self.refreshTabView()
    }
    
    override func viewWillAppear(_ animated: Bool) {
       
        if rotateToPortrait {
            APPDELEGATE.deviceOrientation = .portrait
            let valueOrientation = UIInterfaceOrientation.portrait.rawValue
            UIDevice.current.setValue(valueOrientation, forKey: "orientation")
            UIViewController.attemptRotationToDeviceOrientation()
            self.tabBarController?.tabBar.isHidden = false
            rotateToPortrait = false
            
            if let mainTabViewController = self.parent!.parent as? MainTabViewController {
                mainTabViewController.hideTabbar(flag: false)
            }
        }
    }
    
    func initialize(){
        titleNavigationBar.lblTitle.text = String.localize(key: "title_teams")
        titleNavigationBar.setBackgroundColor()
        titleNavigationBar.hideBackButton()
        
        for i in 0..<tabViewList.count{
            let view = tabViewList[i]
            view.tag = i
            view.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onTabSelected)))
        }
        
        setupTabs()
    }
    
    func setupTabs() {
        let tabClubsVC = Storyboards.Teams.instantiateClubListVC()
        let tabCategoryVC = Storyboards.Teams.instantiateCategoryListVC()
        let tabGroupVC = Storyboards.Teams.instantiateGroupListVC()
        
        viewControllers = [tabClubsVC, tabCategoryVC, tabGroupVC]
        
        addViewControllerToContentView(false)
    }
    
    func addViewControllerToContentView(_ flag: Bool) {
        
        if flag {
            if selectedIndex == 0 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateClubListVC()
            } else if selectedIndex == 1 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateCategoryListVC()
            } else if selectedIndex == 2 {
                viewControllers[selectedIndex] = Storyboards.Teams.instantiateGroupListVC()
            }
        }
        
        let vc = viewControllers[selectedIndex]
        
        addChild(vc)
        vc.view.frame = contentView.bounds
        contentView.addSubview(vc.view)
        vc.didMove(toParent: self)
    }
    
    @objc func onTabSelected(sender : UITapGestureRecognizer) {
        TestFairy.log(String(describing: self) + " onTabSelected")
        
        if let viewValue = sender.view {
            if selectedIndex != viewValue.tag {
                previousIndex = selectedIndex
                selectedIndex = viewValue.tag
                
                tabLabelList[selectedIndex].textColor = .white
                tabLineViewList[selectedIndex].backgroundColor = UIColor.init(named: "teamtabssepcolor")
                
                if selectedIndex != previousIndex {
                    tabLabelList[previousIndex].textColor = UIColor.init(named: "teamtabstextcolor")
                    tabLineViewList[previousIndex].backgroundColor = .clear
                }
                
                // Remove previous view controller
                let previousVC = viewControllers[previousIndex]
                previousVC.willMove(toParent: nil)
                previousVC.view.removeFromSuperview()
                previousVC.removeFromParent()
                
                addViewControllerToContentView(true)
            }
        }
    }
    
    func refreshTabView() {
        tabLabelList[selectedIndex].textColor = .white
        tabLineViewList[selectedIndex].backgroundColor = UIColor.init(named: "teamtabssepcolor")
        
        if selectedIndex != previousIndex {
            tabLabelList[previousIndex].textColor = UIColor.init(named: "teamtabstextcolor")
            tabLineViewList[previousIndex].backgroundColor = .clear
        }
    }
}
