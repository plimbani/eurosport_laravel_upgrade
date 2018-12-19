//
//  ClubListVC.swift
//  ESR
//
//  Created by Pratik Patel on 07/12/18.
//

import UIKit

class ClubListVC: SuperViewController {

    @IBOutlet var txtSearch: UITextField!
    @IBOutlet var table: UITableView!
    
    var tournamentClubList = NSMutableArray()
    var tournamentClubFilterList = NSMutableArray()
    var heightTournamentClubCell: CGFloat = 0
    
    var isSearch = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        sendGetTournamentClubsRequest()
    }
    
    func initialize() {
        txtSearch.placeholder = String.localize(key: "placeholder_search_tab_club")
        txtSearch.setLeftPaddingPoints(35)
        txtSearch.returnKeyType = .done
        txtSearch.layer.cornerRadius = (txtSearch.frame.size.height / 2)
        txtSearch.clipsToBounds = true
        txtSearch.clearButtonMode = .whileEditing
        txtSearch.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
        txtSearch.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TournamentClubCell)
        heightTournamentClubCell = (cellOwner.cell as! TournamentClubCell).getCellHeight()
    }
    
    @objc func textFieldDidChange(textField: UITextField) {
        if let text = txtSearch.text {
            if text.isEmpty {
                isSearch = false
                table.reloadData()
                return
            }
            
            isSearch = true
            
            tournamentClubFilterList = NSMutableArray.init(array: tournamentClubList.filter({
                (($0 as! NSDictionary).value(forKey: "clubName") as! String).contains(text) ||
                (($0 as! NSDictionary).value(forKey: "clubName") as! String).lowercased().contains(text)
            }))
            
            table.reloadData()
        }
    }
    
    func sendGetTournamentClubsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getTournamentClub(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.tournamentClubList = NSMutableArray.init(array: data)
                }
                
                let descriptor: NSSortDescriptor = NSSortDescriptor(key: "clubName", ascending: true)
                self.tournamentClubList = NSMutableArray.init(array: self.tournamentClubList.sortedArray(using: [descriptor]))
                self.table.reloadData()
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        }
    }
}

extension ClubListVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if isSearch {
            return tournamentClubFilterList.count
        }
        
        return tournamentClubList.count
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
        
        if isSearch {
            cell?.record = tournamentClubFilterList[indexPath.row] as! NSDictionary
        } else {
            cell?.record = tournamentClubList[indexPath.row] as! NSDictionary
        }
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let viewController = Storyboards.Teams.instantiateTeamListingVC()
        
        if isSearch {
            viewController.dic = (tournamentClubFilterList[indexPath.row] as! NSDictionary)
        } else {
            viewController.dic = (tournamentClubList[indexPath.row] as! NSDictionary)
        }
        
        viewController.isClubTeam = true
        self.view.endEditing(true)
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
