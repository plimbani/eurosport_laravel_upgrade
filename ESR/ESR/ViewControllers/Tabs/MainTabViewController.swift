//
//  MainTabViewController.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//
import UIKit
import ESTabBarController_swift

class MainTabViewController: ESTabBarController {
    
    let tabFavView = TabBarContentView()
    let tabTournamentView = TabBarContentView()
    let tabTeamView = TabBarContentView()
    let tabAgeCateView = TabBarContentView()
    let tabSettingsView = TabBarContentView()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        setupTabs()
    }
    
    func setupTabs() {
        let tabFavoritesVC = Storyboards.Favourites.instantiateFavouritesVC()
        let tabTournamentVC = Storyboards.Tournament.instantiateTournamentVC()
        let tabTeamsVC = Storyboards.Teams.instantiateTeamsVC()
        let tabAgeCategoriesVC = Storyboards.AgeCategories.instantiateAgeCategoriesVC()
        let tabSettingsVC = Storyboards.Settings.instantiateSettingsVC()
        
        tabFavoritesVC.tabBarItem = getTabBarItem(tabFavView, title: String.localize(key: "title_tab_fav"), image: "tab_favorites", selectedImage: "tab_favorites_1")
        tabTournamentVC.tabBarItem = getTabBarItem(tabTournamentView, title: String.localize(key: "title_tab_tournament"), image: "tab_tournament", selectedImage: "tab_tournament_1")
        tabTeamsVC.tabBarItem = getTabBarItem(tabTeamView, title: String.localize(key: "title_tab_teams"), image: "tab_teams", selectedImage: "tab_teams_1")
        
        
        tabAgeCategoriesVC.tabBarItem = getTabBarItem(tabAgeCateView, title: String.localize(key: "title_tab_agecategories"), image: "tab_age_categories", selectedImage: "tab_age_categories_1")
        tabSettingsVC.tabBarItem = getTabBarItem(tabSettingsView, title: String.localize(key: "title_tab_settings"), image: "tab_settings", selectedImage: "tab_settings_1")
        
        self.viewControllers = [UINavigationController(rootViewController: tabFavoritesVC),
                                UINavigationController(rootViewController: tabTournamentVC),
                                UINavigationController(rootViewController: tabTeamsVC),
                                UINavigationController(rootViewController: tabAgeCategoriesVC),
                                UINavigationController(rootViewController: tabSettingsVC)]

        self.tabBar.tintColor = UIColor.AppColor()
        self.tabBar.barTintColor = .white
        self.tabBar.shadowImage = nil
        APPDELEGATE.tabBarController = self
        self.selectedIndex = 1
    }
    
    override func viewDidAppear(_ animated: Bool) {
        setViewWidthHeight(tabFavView, 24, 29)
        setViewWidthHeight(tabTournamentView, 25, 29)
        setViewWidthHeight(tabTeamView, 31, 29)
        setViewWidthHeight(tabAgeCateView, 32, 29)
        setViewWidthHeight(tabSettingsView, 28, 29)
    }
    
    func setViewWidthHeight(_ view: TabBarContentView, _ width: CGFloat, _ height: CGFloat){
        view.imageView.frame.size.width = width
        view.imageView.frame.size.height = height
    }
    
    override func viewWillLayoutSubviews() {
        super.viewWillLayoutSubviews()
        
        let newTabBarHeight = self.tabBar.frame.size.height + 10
        var newFrame = tabBar.frame
        newFrame.size.height = newTabBarHeight
        newFrame.origin.y = view.frame.size.height - newTabBarHeight
        self.tabBar.frame = newFrame
    }
    
    func getTabBarItem(_ customView: ESTabBarItemContentView, title: String, image: String, selectedImage: String) -> ESTabBarItem {
        return ESTabBarItem.init(customView, title: title, image: UIImage(named: image)?.withRenderingMode(.alwaysOriginal), selectedImage: UIImage(named: selectedImage)?.withRenderingMode(.alwaysOriginal))
    }
}
