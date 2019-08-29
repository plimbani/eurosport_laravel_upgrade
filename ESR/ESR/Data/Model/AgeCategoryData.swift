//
//  AgeCategoryData.swift
//  ESR
//
//  Created by Pratik Patel on 27/08/19.
//

import Foundation

class AgeCategoryData: Codable {
    let dataObj: DataObj?
    
    private enum CodingKeys: String, CodingKey {
        case dataObj = "data"
    }
}

class DataObj: Codable {
    let divisionGroupsList: [DivisionGroupsObj]?
    let roundRobinGroupsList: [DivisionGroupsDataObj]?
    
    private enum CodingKeys: String, CodingKey {
        case divisionGroupsList = "division_groups"
        case roundRobinGroupsList = "round_robin_groups"
    }
}

class DivisionGroupsObj: Codable {
    let divisionGroupsDataList: [DivisionGroupsDataObj]?
    
    private enum CodingKeys: String, CodingKey {
        case divisionGroupsDataList = "data"
    }
}

class DivisionGroupsDataObj: Codable {
    
    let age_category_division_id : Int
    let competation_type: String
    let actual_competition_type: String
    let tournament_competation_template_id: Int
    let tournament_id: Int
    let actual_name: String
    let group_name: String
    let updated_at: String
    let divisionName: String
    let scheduleCount: Int
    let name: String
    let competation_round_no: String
    let is_manual_override_standing: Int
    let id: Int
    let deleted_at: String
    let display_name: String
    let created_at: String
    let team_size: Int
    let divisionId: Int
    let color_code: String
    
    private enum CodingKeys: String, CodingKey {
        case age_category_division_id, competation_type, actual_competition_type, tournament_competation_template_id,
        tournament_id, actual_name, group_name, updated_at, divisionName, scheduleCount, name, competation_round_no,
        is_manual_override_standing, id, deleted_at, display_name, created_at, team_size, divisionId, color_code
    }
}


