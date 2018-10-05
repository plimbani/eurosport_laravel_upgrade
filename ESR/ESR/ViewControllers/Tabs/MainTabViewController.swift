//
//  MainTabViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//
import UIKit

class MainTabViewController: UIViewController {
    
    @IBOutlet var tabButtonList: [UIButton]!
    @IBOutlet var tabLabelList: [UILabel]!
    @IBOutlet var contentView: UIView!
    var selectedIndex = 0
    var previousIndex = 0
    var viewControllers: [UIViewController]!
    
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
    }
    
    func setupTabs() {
        let tabFavoritesVC = Storyboards.Favourites.instantiateFavouritesVC()
        let tabTournamentVC = Storyboards.Tournament.instantiateTournamentVC()
        let tabTeamsVC = Storyboards.Teams.instantiateTeamsVC()
        let tabAgeCategoriesVC = Storyboards.AgeCategories.instantiateAgeCategoriesVC()
        let tabSettingsVC = Storyboards.Settings.instantiateSettingsVC()
        
        viewControllers = [UINavigationController(rootViewController: tabFavoritesVC), UINavigationController(rootViewController: tabTournamentVC), UINavigationController(rootViewController: tabTeamsVC), UINavigationController(rootViewController: tabAgeCategoriesVC), UINavigationController(rootViewController: tabSettingsVC)]
        
        addViewControllerToContentView(false)
    }
    
    func addViewControllerToContentView(_ flag: Bool) {
        tabButtonList[selectedIndex].isSelected = true
        
        if flag {
            if selectedIndex == 0 {
                viewControllers[selectedIndex] = UINavigationController(rootViewController: Storyboards.Favourites.instantiateFavouritesVC())
            } else if selectedIndex == 1 {
                viewControllers[selectedIndex] = UINavigationController(rootViewController: Storyboards.Tournament.instantiateTournamentVC())
            } else if selectedIndex == 2 {
                viewControllers[selectedIndex] = UINavigationController(rootViewController: Storyboards.Teams.instantiateTeamsVC())
            } else if selectedIndex == 3 {
                viewControllers[selectedIndex] = UINavigationController(rootViewController: Storyboards.AgeCategories.instantiateAgeCategoriesVC())
            } else if selectedIndex == 4 {
                viewControllers[selectedIndex] = UINavigationController(rootViewController: Storyboards.Settings.instantiateSettingsVC())
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
