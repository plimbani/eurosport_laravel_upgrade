//
//  TeamListingVC.swift
//  ESR
//
//  Created by Pratik Patel on 18/10/18.
//

import UIKit

class TeamListingVC: SuperViewController {

    @IBOutlet var table: UITableView!
    var teamList = NSArray()
    
    var heightTournamentClubCell: CGFloat = 0
    var clubId = -1
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize() {
        titleNavigationBar.lblTitle.text = String.localize(key: "title_team")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        
        _ = cellOwner.loadMyNibFile(nibName: "TournamentClubCell")
        heightTournamentClubCell = (cellOwner.cell as! TournamentClubCell).getCellHeight()
        
        sendGetTeamListRequest()
    }
    
    func sendGetTeamListRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        parameters["club_id"] = clubId
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getTeamList(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.teamList = data
                }
                
                self.table.reloadData()
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        }
    }
}

extension TeamListingVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension TeamListingVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return teamList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightTournamentClubCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "TournamentClubCell") as? TournamentClubCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "TournamentClubCell")
            cell = cellOwner.cell as? TournamentClubCell
        }
        
        cell?.record = teamList[indexPath.row] as! NSDictionary
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let viewController = Storyboards.Teams.instantiateTeamListingVC()
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}


