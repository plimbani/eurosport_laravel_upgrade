//
//  GroupSummaryMatchesCell.swift
//  ESR
//
//  Created by Pratik Patel on 29/08/18.
//

import UIKit

class GroupSummaryMatchesCell: UITableViewCell {

    var record: TeamFixture!
    
    @IBOutlet var dateView: UIView!
    
    @IBOutlet var lblHomeTeam: UILabel!
    @IBOutlet var lblAwayTeam: UILabel!
    @IBOutlet var lblHomeTeamScore: UILabel!
    @IBOutlet var lblAwayTeamScore: UILabel!
    
    @IBOutlet var lblVenue: UILabel!
    @IBOutlet var lblDate: UILabel!
    @IBOutlet var lblMatchId: UILabel!
    @IBOutlet var lblGroupName: UILabel!
    @IBOutlet var lblRound: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        
        dateView.layer.cornerRadius = dateView.frame.size.width/2
        dateView.clipsToBounds = true
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
    }
    
    func getCellHeight() -> CGFloat {
        return self.frame.size.height
    }
    
    func reloadCell() {
        
        // Sets date
        if record.matchDatetime != NULL_STRING && record.matchDatetimeObj != nil {
            let dateFormatter = DateFormatter()
            dateFormatter.dateFormat = kDateFormat.MMM
            let nameOfMonth = dateFormatter.string(from: record.matchDatetimeObj!)
            dateFormatter.dateFormat = kDateFormat.dd
            let dateOfMonth = dateFormatter.string(from: record.matchDatetimeObj!)
            lblDate.text = dateOfMonth + " " + nameOfMonth
        } else {
            lblDate.text = NULL_STRING
        }
        
        // Venue name
        if record.venueName != NULL_STRING {
            lblVenue.text = record.venueName
            
            if record.pitchNumber != NULL_STRING {
                lblVenue.text = lblVenue.text! + " - " + record.pitchNumber
            }
        } else {
            lblVenue.text = NULL_STRING
        }
        
        if record.displayMatchNumber != NULL_STRING {
            var displayNumberStr = record.displayMatchNumber.replacingOccurrences(of: "@HOME", with: record.displayHomeTeamPlaceholderName)
            displayNumberStr = displayNumberStr.replacingOccurrences(of: "@AWAY", with: record.displayAwayTeamPlaceholderName)
            
            lblMatchId.text = displayNumberStr
        }
        
        if record.groupName != NULL_STRING {
            lblGroupName.text = record.groupName
        } else {
            lblGroupName.text = NULL_STRING
        }
        
        if record.round != NULL_STRING {
            lblRound.text = record.round
        } else {
            lblRound.text = NULL_STRING
        }
        
        // Home team name
        if record.homeId == 0 {
            if record.homeTeamName != NULL_STRING && record.homeTeamName == "@^^@" {
                if record.competitionActualName != NULL_STRING && record.competitionActualName.contains("Group") {
                    lblHomeTeam.text = record.homePlaceholder
                } else if record.competitionActualName != NULL_STRING && record.competitionActualName.contains("Pos") {
                    lblHomeTeam.text = "Pos - " + record.homePlaceholder
                } else {
                    if record.homeTeam != NULL_STRING {
                        lblHomeTeam.text = record.homeTeam
                    } else {
                        lblHomeTeam.text = NULL_STRING
                    }
                }
            } else {
                if record.displayHomeTeamPlaceholderName != NULL_STRING {
                    lblHomeTeam.text = record.displayHomeTeamPlaceholderName
                } else {
                    lblHomeTeam.text = NULL_STRING
                }
            }
        } else {
            if record.homeTeam != NULL_STRING {
                lblHomeTeam.text = record.homeTeam
            } else {
                lblHomeTeam.text = NULL_STRING
            }
        }
        
        // Away team name
        if record.awayId == 0 {
            if record.awayTeamName != NULL_STRING && record.awayTeamName == "@^^@" {
                if record.competitionActualName != NULL_STRING && record.competitionActualName.contains("Group") {
                    lblAwayTeam.text = record.awayPlaceholder
                } else if record.competitionActualName != NULL_STRING && record.competitionActualName.contains("Pos") {
                    lblAwayTeam.text = "Pos - " + record.awayPlaceholder
                } else {
                    if record.awayTeam != NULL_STRING {
                        lblAwayTeam.text = record.awayTeam
                    } else {
                        lblAwayTeam.text = NULL_STRING
                    }
                }
            } else {
                if record.displayAwayTeamPlaceholderName != NULL_STRING {
                    lblAwayTeam.text = record.displayAwayTeamPlaceholderName
                } else {
                    lblAwayTeam.text = NULL_STRING
                }
            }
        } else {
            if record.awayTeam != NULL_STRING {
                lblAwayTeam.text = record.awayTeam
            } else {
                lblAwayTeam.text = NULL_STRING
            }
        }
        
        var homeScore = NULL_ID
        var awayScore = NULL_ID
        
        // Home team score
        if record.homeScore != NULL_ID {
            lblHomeTeamScore.text = "\(record.homeScore)"
            homeScore = record.homeScore
        } else {
            lblHomeTeamScore.text = NULL_STRING
        }
        
        // Away team score
        if record.awayScore != NULL_ID {
            lblAwayTeamScore.text = "\(record.awayScore)"
            awayScore = record.awayScore
        } else {
            lblAwayTeamScore.text = NULL_STRING
        }
        
        if homeScore != NULL_ID && awayScore != NULL_ID {
            if homeScore == awayScore {
                lblHomeTeamScore.textColor = .AppColor()
                lblAwayTeamScore.textColor = .AppColor()
                
                lblHomeTeam.textColor = .AppColor()
                lblAwayTeam.textColor = .AppColor()
            } else if homeScore > awayScore {
                lblHomeTeamScore.textColor = .AppColor()
                lblAwayTeamScore.textColor = .black
                
                lblHomeTeam.textColor = .AppColor()
                lblAwayTeam.textColor = .black
            } else {
                lblHomeTeamScore.textColor = .black
                lblAwayTeamScore.textColor = .AppColor()
                
                lblHomeTeam.textColor = .black
                lblAwayTeam.textColor = .AppColor()
            }
        } else {
            lblHomeTeamScore.textColor = .black
            lblAwayTeamScore.textColor = .black
            
            lblHomeTeam.textColor = .black
            lblAwayTeam.textColor = .black
        }
        
        if record.isResultOverride != NULL_ID && record.isResultOverride == 1 {
            if record.matchStatus != NULL_STRING {
                
                if record.matchStatus == "Walk-over" || record.matchStatus == "Penalties" || record.matchStatus == "Abandoned" {
                    if record.matchWinner != NULL_STRING && record.homeId != NULL_ID && record.homeId == Int(record.matchWinner) {
                        lblHomeTeamScore.text = lblHomeTeamScore.text! + "*"
                        lblAwayTeamScore.text = lblAwayTeamScore.text! + " "
                    } else if record.matchWinner != NULL_STRING && record.awayId != NULL_ID && record.awayId == Int(record.matchWinner) {
                        lblAwayTeamScore.text = lblAwayTeamScore.text! + "*"
                        lblHomeTeamScore.text = lblHomeTeamScore.text! + " "
                    }
                }
            }
        }
    }
}
