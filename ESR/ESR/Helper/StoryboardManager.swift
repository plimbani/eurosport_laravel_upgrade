//
//  StoryboardManager.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

public protocol IdentifiableProtocol: Equatable {
    var storyboardIdentifier: String? { get }
}

// MARK: - Storyboards
extension UIStoryboard {
    func instantiateViewController<T: UIViewController>(ofType type: T.Type) -> T? where T: IdentifiableProtocol {
        let instance = type.init()
        if let identifier = instance.storyboardIdentifier {
            return self.instantiateViewController(withIdentifier: identifier) as? T
        }
        return nil
    }
}

protocol Storyboard {
    static var storyboard: UIStoryboard { get }
    static var identifier: String { get }
}

struct Storyboards {
    
    struct Main: Storyboard {
        
        static let identifier = "Main"
        
        static var storyboard: UIStoryboard {
            return UIStoryboard(name: self.identifier, bundle: nil)
        }
        
        static func instantiateInitialViewController() -> UINavigationController {
            return self.storyboard.instantiateInitialViewController() as! UINavigationController
        }
        
        static func instantiateViewController(withIdentifier identifier: String) -> UIViewController {
            return self.storyboard.instantiateViewController(withIdentifier: identifier)
        }
        
        static func instantiateViewController<T: UIViewController>(ofType type: T.Type) -> T? where T: IdentifiableProtocol {
            return self.storyboard.instantiateViewController(ofType: type)
        }
        
        static func instantiateMainVC() -> MainTabViewController {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.MainTabViewController) as! MainTabViewController
        }
        
        static func instantiateMapVC() -> MapVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.MapVC) as! MapVC
        }
        
        static func instantiateLoginVC() -> LoginVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.LoginVC) as! LoginVC
        }
        
        static func instantiateLandingVC() -> LandingVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.LandingVC) as! LandingVC
        }
        
        static func instantiateForgotPasswordVC() -> ForgotPasswordVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.ForgotPasswordVC) as! ForgotPasswordVC
        }
        
        static func instantiateCreateAccountVC() -> CreateAccountVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.CreateAccountVC) as! CreateAccountVC
        }
    }
    
    struct Favourites: Storyboard {
        static let identifier = "Favourites"
        
        static var storyboard: UIStoryboard {
            return UIStoryboard(name: self.identifier, bundle: nil)
        }
        
        static func instantiateFavouritesVC() -> TabFavouritesVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.TabFavouritesVC) as! TabFavouritesVC
        }
    }
    
    struct Tournament: Storyboard {
        static let identifier = "Tournament"
        
        static var storyboard: UIStoryboard {
            return UIStoryboard(name: self.identifier, bundle: nil)
        }
        
        static func instantiateTournamentVC() -> TabTournamentVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.TabTournamentVC) as! TabTournamentVC
        }
        
        static func instantiateFinalPlacingsVC() -> FinalPlacingsVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.FinalPlacingsVC) as! FinalPlacingsVC
        }
    }
    
    struct AgeCategories: Storyboard {
        static let identifier = "AgeCategories"
        
        static var storyboard: UIStoryboard {
            return UIStoryboard(name: self.identifier, bundle: nil)
        }
        
        static func instantiateAgeCategoriesVC() -> TabAgeCategoriesVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.TabAgeCategoriesVC) as! TabAgeCategoriesVC
        }
        
        static func instantiateAgeCategoriesGroupsVC() -> AgeCategoriesGroupsVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.AgeCategoriesGroupsVC) as! AgeCategoriesGroupsVC
        }
        
        static func instantiateAgeCategoriesGroupsSummaryVC() -> AgeCategoriesGroupsSummaryVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.AgeCategoriesGroupsSummaryVC) as! AgeCategoriesGroupsSummaryVC
        }
        
        static func instantiateGroupDetailsVC() -> GroupDetailsVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.GroupDetailsVC) as! GroupDetailsVC
        }
        
        static func instantiateMatchInfoVC() -> MatchInfoVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.MatchInfoVC) as! MatchInfoVC
        }
        
        static func instantiateVenueVC() -> VenueVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.VenueVC) as! VenueVC
        }
    }
    
    struct Teams: Storyboard {
        static let identifier = "Teams"
        
        static var storyboard: UIStoryboard {
            return UIStoryboard(name: self.identifier, bundle: nil)
        }
        
        static func instantiateTeamsVC() -> TabTeamsVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.TabTeamsVC) as! TabTeamsVC
        }
        
        static func instantiateClubListVC() -> ClubListVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.ClubListVC) as! ClubListVC
        }
        
        static func instantiateCategoryListVC() -> CategoryListVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.CategoryListVC) as! CategoryListVC
        }
        
        static func instantiateGroupListVC() -> GroupListVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.GroupListVC) as! GroupListVC
        }
        
        static func instantiateTeamListingVC() -> TeamListingVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.TeamListingVC) as! TeamListingVC
        }
    }
    
    struct Settings: Storyboard {
        static let identifier = "Settings"
        
        static var storyboard: UIStoryboard {
            return UIStoryboard(name: self.identifier, bundle: nil)
        }
        
        static func instantiateSettingsVC() -> TabSettingsVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.TabSettingsVC) as! TabSettingsVC
        }
        
        static func instantiateProfileVC() -> ProfileVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.ProfileVC) as! ProfileVC
        }
        
        static func instantiateNotificationAndSoundVC() -> NotificationAndSoundVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.NotificationAndSoundVC) as! NotificationAndSoundVC
        }
        
        static func instantiatePrivacyAndTermsVC() -> PrivacyAndTermsVC {
            return self.storyboard.instantiateViewController(withIdentifier: kViewController.PrivacyAndTermsVC) as! PrivacyAndTermsVC
        }
    }
}


