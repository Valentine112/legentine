import React, { useEffect, useState } from "react";
import "./css/home.css";
import {
	LineChart,
	Line,
	CartesianGrid,
	XAxis,
	YAxis,
	ResponsiveContainer,
} from "recharts";
import useDash from "../../hooks/useDash";

const Home = () => {
	const {users, posts} = useDash();
	const [category, setCategory] = useState({})

	
	console.log(typeof posts.category)

	return (
		<div className="home container">
			<div className="box-holder row justify-content-around">
				{/* User chart */}
				<div className="boxes col-11 col-md-8 col-lg-10">
					<section className="section-header">
						<p>Users</p>
					</section>
					<section>
						<div>
							<ResponsiveContainer>
								<LineChart
									width={400}
									height={400}
									data={users.timeFrame}
								>
									<Line
										type="monotone"
										dataKey="uv"
										stroke="#8884d8"
									/>
									<CartesianGrid stroke="#ccc" />
									<XAxis dataKey="name" />
									<YAxis />
								</LineChart>
							</ResponsiveContainer>
						</div>
					</section>
					<div>
						<p>Total users - {users.total}</p>
					</div>
				</div>

				{/* Post chart */}
				<div className="boxes col-11 col-md-8 col-lg-10 mt-3">
					<section className="section-header">
						<p>Posts</p>
					</section>
					<section>
						<div>
							<ResponsiveContainer>
								<LineChart
									width={400}
									height={400}
									data={posts.timeFrame}
								>
									<Line
										type="monotone"
										dataKey="uv"
										stroke="#8884d8"
									/>
									<CartesianGrid stroke="#ccc" />
									<XAxis dataKey="name" />
									<YAxis />
								</LineChart>
							</ResponsiveContainer>
						</div>
					</section>
					<div className="row justify-content-left chart-details">
						<div className="col-12">
							<p>Total post - {posts.total}</p>
						</div>
							<div
								className="col-12 col-md-3 col-lg-2 category"
								key={"category"}
							>
								<p>
									{category.forEach((elem, ind) => (
										<div
										className="col-12 col-md-3 col-lg-2 category"
										key={"category" + ind}
										>
											<p>
												{elem} - {category[elem]}
											</p>
										</div>
									))}
								</p>
							</div>
					</div>
				</div>

				<div></div>

				<div className=""></div>

				{/* Active users, Feedbacks,  */}
			</div>
		</div>
	);
};

export default Home;
