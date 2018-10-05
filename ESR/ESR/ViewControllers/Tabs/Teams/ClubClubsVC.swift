//
//  ClubClubsVC.swift
//  ESR
//
//  Created by Pratik Patel on 10/09/18.
//

import UIKit

class ClubClubsVC: SuperViewController {

    @IBOutlet var txtSearch: UITextField!
    @IBOutlet var table: UITableView!
    
    var tournamentClubList = NSArray()
    var heightTournamentClubCell: CGFloat = 0
    
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
        txtSearch.delegate = self
        txtSearch.returnKeyType = .done
        txtSearch.layer.cornerRadius = (txtSearch.frame.size.height / 2)
        txtSearch.clipsToBounds = true
        txtSearch.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TournamentClubCell)
        heightTournamentClubCell = (cellOwner.cell as! TournamentClubCell).getCellHeight()
    }
    
    func sendGetTournamentClubsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["tournament_id"] = userData.tournamentId
        }
        
        ApiManager().getTournamentClub(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.tournamentClubList = data
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

extension ClubClubsVC: UITextFieldDelegate {
    func textFieldDidBeginEditing(_ textField: UITextField) {
        
    }
}

extension ClubClubsVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
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
        cell?.record = tournamentClubList[indexPath.row] as! NSDictionary
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsSummaryVC()
        viewController.dicGroup = (tournamentClubList[indexPath.row] as! NSDictionary)
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
