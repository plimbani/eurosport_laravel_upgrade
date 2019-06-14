//
//  MatchInfoVC.swift
//  ESR
//
//  Created by Pratik Patel on 03/09/18.
//

import UIKit

class MatchInfoVC: SuperViewController {

    @IBOutlet var stackViewTshirt: UIStackView!
    @IBOutlet var stackviewTop: UIStackView!
    @IBOutlet var stackviewMiddle: UIStackView!
    // Team 1
    @IBOutlet var lblTeam1Score: UILabel!
    @IBOutlet var lblTeam1Name: UILabel!
    @IBOutlet var imgViewTeam1Flag: UIImageView!
    @IBOutlet var imgViewTeam1Tshirt: UIImageView!
    @IBOutlet var imgViewTeam1Short: UIImageView!
    // Team 2
    @IBOutlet var lblTeam2Score: UILabel!
    @IBOutlet var lblTeam2Name: UILabel!
    @IBOutlet var imgViewTeam2Flag: UIImageView!
    @IBOutlet var imgViewTeam2Tshirt: UIImageView!
    @IBOutlet var imgViewTeam2Short: UIImageView!
    
    @IBOutlet var lblDate: UILabel!
    @IBOutlet var lblRefereeName: UILabel!
    @IBOutlet var lblAgeNGroupInfo: UILabel!
    @IBOutlet var lblMatchId: UILabel!
    @IBOutlet var lblPlacing: UILabel!
    
    @IBOutlet var viewVenue: UIView!
    @IBOutlet var lblVenue: UILabel!
    
    @IBOutlet var viewWinnerStatus: UIView!
    @IBOutlet var lblWinnerStatus: UILabel!
    var dicTeamFixture: TeamFixture!
    
    @IBOutlet var heightConstraintViewWinnerStatus: NSLayoutConstraint!
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
        setData()
    }

    override func viewDidAppear(_ animated: Bool) {
        ApplicationData.setBorder(viewVenue, Color: .gray, thickness: 1.0, type: ViewBorderType.left)
        ApplicationData.setBorder(viewVenue, Color: .gray, thickness: 1.0, type: ViewBorderType.bottom)
        ApplicationData.setBorder(viewVenue, Color: .gray, thickness: 1.0, type: ViewBorderType.right)
        ApplicationData.setBorder(stackviewTop, Color: .gray, thickness: 1.0, type: ViewBorderType.top)
        ApplicationData.setBorder(stackviewTop, Color: .gray, thickness: 1.0, type: ViewBorderType.left)
        ApplicationData.setBorder(stackviewTop, Color: .gray, thickness: 1.0, type: ViewBorderType.right)
        ApplicationData.setBorder(stackviewMiddle, Color: .gray, thickness: 1.0, type: ViewBorderType.left)
        ApplicationData.setBorder(stackviewMiddle, Color: .gray, thickness: 1.0, type: ViewBorderType.right)
        ApplicationData.setBorder(stackViewTshirt, Color: .gray, thickness: 1.0, type: ViewBorderType.left)
        ApplicationData.setBorder(stackViewTshirt, Color: .gray, thickness: 1.0, type: ViewBorderType.right)
    }
    
    func initialize() {
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        titleNavigationBar.lblTitle.text = String.localize(key: "title_match_info")
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        let subView = UIView(frame: stackviewMiddle.bounds)
        subView.backgroundColor = UIColor.labelSlectionBg
        subView.autoresizingMask = [.flexibleWidth, .flexibleHeight]
        stackviewMiddle.insertSubview(subView, at: 0)
        
        self.viewVenue.addGestureRecognizer(UITapGestureRecognizer(target: self, action:  #selector(self.onVenueViewPressed)))
    }
    
    @objc func onVenueViewPressed(sender : UITapGestureRecognizer) {
        let viewController = Storyboards.AgeCategories.instantiateVenueVC()
        viewController.dicTeamFixture = dicTeamFixture
        self.navigationController?.pushViewController(viewController, animated: true)
    }
    
    func setData() {
        
        var homeScore = NULL_ID
        var awayScore = NULL_ID
        
        // Home team score
        if dicTeamFixture.homeScore != NULL_ID {
            lblTeam1Score.text = "\(dicTeamFixture.homeScore)"
            homeScore = dicTeamFixture.homeScore
        } else {
            lblTeam1Score.text = NULL_STRING
        }
        
        if dicTeamFixture.awayScore != NULL_ID {
            lblTeam2Score.text = "\(dicTeamFixture.awayScore)"
            awayScore = dicTeamFixture.awayScore
        } else {
            lblTeam2Score.text = NULL_STRING
        }
        
        if homeScore != NULL_ID && awayScore != NULL_ID {
            if homeScore == awayScore {
                lblTeam1Score.textColor = .AppColor()
                lblTeam2Score.textColor = .AppColor()
                
                lblTeam1Name.textColor = .AppColor()
                lblTeam2Name.textColor = .AppColor()
            } else if homeScore > awayScore {
                lblTeam1Score.textColor = .AppColor()
                lblTeam2Score.textColor = .black
                
                lblTeam1Name.textColor = .AppColor()
                lblTeam2Name.textColor = .black
            } else {
                lblTeam1Score.textColor = .black
                lblTeam2Score.textColor = .AppColor()
                
                lblTeam1Name.textColor = .black
                lblTeam2Name.textColor = .AppColor()
            }
        } else {
            lblTeam1Score.textColor = .black
            lblTeam2Score.textColor = .black
            
            lblTeam1Name.textColor = .black
            lblTeam2Name.textColor = .black
        }
        
        // Home team name
        if dicTeamFixture.homeId == 0 {
            if dicTeamFixture.homeTeamName != NULL_STRING && dicTeamFixture.homeTeamName == "@^^@" {
                if dicTeamFixture.competitionActualName != NULL_STRING && dicTeamFixture.competitionActualName.contains("Group") {
                    lblTeam1Name.text = dicTeamFixture.homePlaceholder
                } else if dicTeamFixture.competitionActualName != NULL_STRING && dicTeamFixture.competitionActualName.contains("Pos") {
                    lblTeam1Name.text = "Pos - " + dicTeamFixture.homePlaceholder
                } else {
                    if dicTeamFixture.homeTeam != NULL_STRING {
                        lblTeam1Name.text = dicTeamFixture.homeTeam
                    } else {
                        lblTeam1Name.text = NULL_STRING
                    }
                }
            } else {
                if dicTeamFixture.displayHomeTeamPlaceholderName != NULL_STRING {
                    lblTeam1Name.text = dicTeamFixture.displayHomeTeamPlaceholderName
                } else {
                    lblTeam1Name.text = NULL_STRING
                }
            }
        } else {
            if dicTeamFixture.homeTeam != NULL_STRING {
                lblTeam1Name.text = dicTeamFixture.homeTeam
            } else {
                lblTeam1Name.text = NULL_STRING
            }
        }
        
        // Away team name
        if dicTeamFixture.awayId == 0 {
            if dicTeamFixture.awayTeamName != NULL_STRING && dicTeamFixture.awayTeamName == "@^^@" {
                if dicTeamFixture.competitionActualName != NULL_STRING && dicTeamFixture.competitionActualName.contains("Group") {
                    lblTeam2Name.text = dicTeamFixture.awayPlaceholder
                } else if dicTeamFixture.competitionActualName != NULL_STRING && dicTeamFixture.competitionActualName.contains("Pos") {
                    lblTeam2Name.text = "Pos - " + dicTeamFixture.awayPlaceholder
                } else {
                    if dicTeamFixture.awayTeam != NULL_STRING {
                        lblTeam2Name.text = dicTeamFixture.awayTeam
                    } else {
                        lblTeam2Name.text = NULL_STRING
                    }
                }
            } else {
                if dicTeamFixture.displayAwayTeamPlaceholderName != NULL_STRING {
                    lblTeam2Name.text = dicTeamFixture.displayAwayTeamPlaceholderName
                } else {
                    lblTeam2Name.text = NULL_STRING
                }
            }
        } else {
            if dicTeamFixture.awayTeam != NULL_STRING {
                lblTeam2Name.text = dicTeamFixture.awayTeam
            } else {
                lblTeam2Name.text = NULL_STRING
            }
        }
        
        var refreeName = NULL_STRING
        
        if dicTeamFixture.firstName != NULL_STRING {
            refreeName = dicTeamFixture.firstName + " "
        }
        
        if dicTeamFixture.lastName != NULL_STRING {
            refreeName += dicTeamFixture.lastName
        }
        
        if refreeName != NULL_STRING {
            lblRefereeName.text = refreeName
        } else {
            stackviewMiddle.removeArrangedSubview(lblRefereeName)
            lblRefereeName.removeFromSuperview()
        }
        
        var matchStatusAndWinner = NULL_STRING
        
        if dicTeamFixture.matchStatus != NULL_STRING {
            matchStatusAndWinner = dicTeamFixture.matchStatus
        }
        
        if dicTeamFixture.matchWinner != NULL_STRING {
            matchStatusAndWinner += " - " + dicTeamFixture.matchWinner
        }
        
        if matchStatusAndWinner != NULL_STRING {
            matchStatusAndWinner += " " + String.localize(key: "string_is_the_winner")
        } else {
            heightConstraintViewWinnerStatus.constant = 0
            viewWinnerStatus.clipsToBounds = true
            viewWinnerStatus.updateConstraints()
        }
        
        // Image
        if dicTeamFixture.homeFlagLogo != NULL_STRING {
            imgViewTeam1Flag.sd_setImage(with: URL(string: dicTeamFixture.homeFlagLogo), placeholderImage:nil)
        } else {
            imgViewTeam1Flag.contentMode = .scaleAspectFit
            imgViewTeam1Flag.image = UIImage.init(named: "globe")
        }
        
        if dicTeamFixture.awayFlagLogo != NULL_STRING {
            imgViewTeam2Flag.sd_setImage(with: URL(string: dicTeamFixture.awayFlagLogo), placeholderImage:nil)
        } else {
            imgViewTeam2Flag.contentMode = .scaleAspectFit
            imgViewTeam2Flag.image = UIImage.init(named: "globe")
        }
        
        if dicTeamFixture.matchDatetime != NULL_STRING && dicTeamFixture.matchDatetimeObj != nil{
            let dateFormatter = DateFormatter()
//            dateFormatter.dateFormat = kDateFormat.MMM
//            let nameOfMonth = dateFormatter.string(from: dicTeamFixture.matchDatetimeObj!)
//            dateFormatter.dateFormat = kDateFormat.dd
//            let dateOfMonth = dateFormatter.string(from: dicTeamFixture.matchDatetimeObj!)
//            dateFormatter.dateFormat = kDateFormat.hhmm
//            let strTime = dateFormatter.string(from: dicTeamFixture.matchDatetimeObj!)
            
            dateFormatter.dateFormat = kDateFormat.format6
            let formattedDate = dateFormatter.string(from: dicTeamFixture.matchDatetimeObj!)
            
            //lblDate.text = dateOfMonth + " " + nameOfMonth
            //lblTime.text = strTime
            
            lblDate.text = formattedDate
        } else {
            lblDate.text = NULL_STRING
        }
        
        if dicTeamFixture.groupName != NULL_STRING {
            lblAgeNGroupInfo.text = dicTeamFixture.groupName
        } else {
            stackviewMiddle.removeArrangedSubview(lblAgeNGroupInfo)
            lblAgeNGroupInfo.removeFromSuperview()
        }
        
        var matchId = NULL_STRING
        
        if dicTeamFixture.displayMatchNumber != NULL_STRING {
            matchId = String.localize(key: "string_match_id")
            var displayNumberStr = dicTeamFixture.displayMatchNumber.replacingOccurrences(of: "@HOME", with: dicTeamFixture.displayHomeTeamPlaceholderName)
            displayNumberStr = displayNumberStr.replacingOccurrences(of: "@AWAY", with: dicTeamFixture.displayAwayTeamPlaceholderName)
            lblMatchId.text = matchId + " " + displayNumberStr
        } else {
            lblMatchId.text = NULL_STRING
        }
        
        var venueDetail = NULL_STRING
        if dicTeamFixture.venueName != NULL_STRING {
            venueDetail = dicTeamFixture.venueName
        }
        
        if dicTeamFixture.pitchNumber != NULL_STRING {
            venueDetail += " - " + dicTeamFixture.pitchNumber
        }
        
        if venueDetail != NULL_STRING {
            lblVenue.text = venueDetail
        } else {
            lblVenue.text = NULL_STRING
        }
        
        if dicTeamFixture.actualRound != NULL_STRING && dicTeamFixture.actualRound == "Elimination" {
            
            var placingStr = String.localize(key: "string_placing")
            
            if dicTeamFixture.position != NULL_STRING {
                placingStr += " " + dicTeamFixture.position
            } else {
                placingStr += " " + String.localize(key: "string_na")
            }
            
            lblPlacing.text = placingStr
        } else {
            stackviewMiddle.removeArrangedSubview(lblPlacing)
            lblPlacing.removeFromSuperview()
        }
        
        if dicTeamFixture.isResultOverride != NULL_ID && dicTeamFixture.isResultOverride == 1 {
            if dicTeamFixture.matchStatus != NULL_STRING {
                if dicTeamFixture.matchWinner != NULL_STRING && dicTeamFixture.homeId != NULL_ID && dicTeamFixture.homeId == Int(dicTeamFixture.matchWinner) {
                    lblTeam1Score.text = lblTeam1Score.text! + "*"
                } else if dicTeamFixture.matchWinner != NULL_STRING && dicTeamFixture.awayId != NULL_ID && dicTeamFixture.awayId == Int(dicTeamFixture.matchWinner) {
                    lblTeam2Score.text = lblTeam2Score.text! + "*"
                }
                
                if dicTeamFixture.matchStatus == "Walk-over" {
                    lblWinnerStatus.text = String.localize(key: "string_walkover_win")
                } else if dicTeamFixture.matchStatus == "Penalties" {
                    lblWinnerStatus.text = String.localize(key: "string_penalties_win")
                } else if dicTeamFixture.matchStatus == "Abandoned" {
                    lblWinnerStatus.text = String.localize(key: "string_abandoned_win")
                }
            }
        }
        
        imgViewTeam1Tshirt.image = UIImage.init(named: "tshirt")
        imgViewTeam1Short.image = UIImage.init(named: "short")
        imgViewTeam2Tshirt.image = UIImage.init(named: "tshirt")
        imgViewTeam2Short.image = UIImage.init(named: "short")
        
        if dicTeamFixture.homeTeamShirtColor != NULL_STRING && dicTeamFixture.homeTeamShirtColor != "#FFFFFF" {
            imgViewTeam1Tshirt.setImageColor(color: UIColor.init(hexString: dicTeamFixture.homeTeamShirtColor))
        }
        
        if dicTeamFixture.homeTeamShortsColor != NULL_STRING && dicTeamFixture.homeTeamShortsColor != "#FFFFFF" {
            imgViewTeam1Short.setImageColor(color: UIColor.init(hexString: dicTeamFixture.homeTeamShortsColor))
        }
        
        if dicTeamFixture.awayTeamShirtColor != NULL_STRING && dicTeamFixture.awayTeamShirtColor != "#FFFFFF" {
            imgViewTeam2Tshirt.setImageColor(color: UIColor.init(hexString: dicTeamFixture.awayTeamShirtColor))
        }
        
        if dicTeamFixture.awayTeamShortsColor != NULL_STRING && dicTeamFixture.awayTeamShortsColor != "#FFFFFF" {
            imgViewTeam2Short.setImageColor(color: UIColor.init(hexString: dicTeamFixture.awayTeamShortsColor))
        }
        
        if dicTeamFixture.homeTeamShirtColor == NULL_STRING &&
           dicTeamFixture.homeTeamShortsColor == NULL_STRING &&
           dicTeamFixture.awayTeamShirtColor == NULL_STRING &&
            dicTeamFixture.awayTeamShortsColor == NULL_STRING {
            stackViewTshirt.isHidden = true
        }
        
    }
    
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
}

extension MatchInfoVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}
