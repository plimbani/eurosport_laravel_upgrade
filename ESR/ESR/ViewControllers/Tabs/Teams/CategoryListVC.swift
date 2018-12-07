//
//  CategoryListVC.swift
//  ESR
//
//  Created by Pratik Patel on 07/12/18.
//

import UIKit

class CategoryListVC: SuperViewController {

    @IBOutlet var txtSearch: UITextField!
    @IBOutlet var table: UITableView!
    @IBOutlet var searchView: UIView!
    @IBOutlet var imgSearch: UIImageView!
    
    @IBOutlet var heightConstraintSeachView: NSLayoutConstraint!
    @IBOutlet var heightConstraintTitleNavigationBar: NSLayoutConstraint!
    
    var ageCategoriesList = NSArray()
    var heightAgeCategoryCell: CGFloat = 0
    var isFromTournament = false
    var tournamentId = NULL_ID
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        sendAgeCategoriesRequest()
    }
    
    func initialize() {
        txtSearch.placeholder = String.localize(key: "placeholder_search_tab_category")
        txtSearch.setLeftPaddingPoints(35)
        txtSearch.delegate = self
        txtSearch.returnKeyType = .done
        txtSearch.layer.cornerRadius = (txtSearch.frame.size.height / 2)
        txtSearch.clipsToBounds = true
        txtSearch.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.AgeCategoryCell)
        heightAgeCategoryCell = (cellOwner.cell as! AgeCategoryCell).getCellHeight()
        
        if isFromTournament {
            heightConstraintSeachView.constant = 0
            titleNavigationBar.lblTitle.text = String.localize(key: "title_final_placing")
            titleNavigationBar.delegate = self
            titleNavigationBar.setBackgroundColor()
            searchView.updateConstraints()
            imgSearch.image = UIImage()
        } else {
            heightConstraintTitleNavigationBar.constant = 0
        }
    }
    
    func sendAgeCategoriesRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getAgeCategories(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.ageCategoriesList = data
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

extension CategoryListVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension CategoryListVC: UITextFieldDelegate {
    func textFieldDidBeginEditing(_ textField: UITextField) {
        
    }
}

extension CategoryListVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return ageCategoriesList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightAgeCategoryCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "AgeCategoryCell") as? AgeCategoryCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "AgeCategoryCell")
            cell = cellOwner.cell as? AgeCategoryCell
        }
        cell?.record = ageCategoriesList[indexPath.row] as! NSDictionary
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        
        if isFromTournament {
            let viewController = Storyboards.Tournament.instantiateFinalPlacingsVC()
            viewController.ageCategoryId = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "id") as! Int
            self.navigationController?.pushViewController(viewController, animated: true)
            return
        }
        
        let viewController = Storyboards.Teams.instantiateTeamListingVC()
        viewController.isClubsCategoryTeam = true
        viewController.dic = (ageCategoriesList[indexPath.row] as! NSDictionary)
        self.navigationController?.pushViewController(viewController, animated: true)
        
    }
}
